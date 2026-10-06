<?php
// Backlink Hub Publisher v0.2（Code Snippets 版）
// 新功能：插入舊文（niche edit）、drip 延遲刊登、anchor 輪換、一撳撤 link
// 出版方永遠有最終審批權；冇嘢會未經批准就上線。

class BHP_Plugin {
    const OPT = 'bhp_settings';
    const API = 'https://seo-hub-imbe.onrender.com/v1/hub';
    const PENDING = 'bhp_pending_inserts'; // 待審插入队列
    const ANCHOR_LOG = 'bhp_anchor_log';   // domain → 用過嘅 anchors

    public static function init() {
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_init', [__CLASS__, 'register']);
        add_action('bhp_poll', [__CLASS__, 'poll']);
        add_action('bhp_do_insert', [__CLASS__, 'doInsert']);       // drip：單次插入事件
        add_action('bhp_do_publish', [__CLASS__, 'doPublish']);     // drip：單次出版事件
        add_action('transition_post_status', [__CLASS__, 'onPublish'], 10, 3);
        add_filter('cron_schedules', function ($s) {
            $s['bhp_15min'] = ['interval' => 900, 'display' => 'Every 15 min (Backlink Hub)'];
            return $s;
        });
        if (!wp_next_scheduled('bhp_poll')) wp_schedule_event(time(), 'bhp_15min', 'bhp_poll');
        add_action('admin_notices', [__CLASS__, 'notices']);
    }

    private static function opt($k, $d = '') {
        $o = get_option(self::OPT, []);
        return $o[$k] ?? $d;
    }

    public static function menu() {
        add_options_page('Backlink Hub', 'Backlink Hub', 'manage_options', 'bhp', [__CLASS__, 'page']);
    }

    public static function register() {
        register_setting(self::OPT, self::OPT, ['sanitize_callback' => [__CLASS__, 'sanitize']]);
    }

    public static function sanitize($in) {
        $out = get_option(self::OPT, []);
        $out['api_key']     = preg_match('/^rmh_[a-f0-9]{48}$/', trim($in['api_key'] ?? '')) ? trim($in['api_key']) : $out['api_key'] ?? '';
        $out['post_status'] = in_array($in['post_status'] ?? '', ['draft', 'publish']) ? $in['post_status'] : 'draft';
        $out['min_score']   = max(0, min(100, intval($in['min_score'] ?? 70)));
        $out['category']    = intval($in['category'] ?? 0);
        $out['mode']        = in_array($in['mode'] ?? '', ['new_post', 'insert']) ? $in['mode'] : 'new_post';
        $out['drip']        = !empty($in['drip']) ? 1 : 0;
        $out['rel_mode']    = in_array($in['rel_mode'] ?? '', ['auto', 'sponsored', 'dofollow']) ? $in['rel_mode'] : 'auto';
        $ep = esc_url_raw($in['ai_endpoint'] ?? '');
        $out['ai_endpoint'] = (strpos($ep, 'https://') === 0 || $ep === '') ? $ep : ($out['ai_endpoint'] ?? ''); // 只准 https，防 SSRF
        $out['ai_model']    = sanitize_text_field($in['ai_model'] ?? '');
        if (!empty($in['ai_key'])) $out['ai_key'] = sanitize_text_field($in['ai_key']); // 留空 = 唔改
        return $out;
    }

    /* ────────────────────────── Admin 頁 ────────────────────────── */

    public static function page() {
        if (!current_user_can('manage_options')) return;
        self::handleActions();
        $key = self::opt('api_key');
        ?>
        <div class="wrap"><h1>Backlink Hub Publisher <small style="font-size:12px;color:#888">v0.2</small></h1>
        <p>攞 API key：<a href="https://recmoment.net/hub-portal/" target="_blank">recmoment.net/hub-portal</a>。
        提案經你批准之後先會郁你嘅站：可以揀出新文章，或者插入現有舊文（niche edit，值錢好多）。刊登後自動回報 Hub 驗證計分。</p>
        <form method="post" action="options.php">
        <?php settings_fields(self::OPT); ?>
        <table class="form-table">
          <tr><th>Hub API Key</th><td><input type="password" name="<?php echo self::OPT; ?>[api_key]" value="<?php echo esc_attr($key); ?>" class="regular-text" autocomplete="off">
          <p class="description">rmh_ 開頭，48 位 hex</p></td></tr>
          <tr><th>刊登模式</th><td><select name="<?php echo self::OPT; ?>[mode]">
            <option value="new_post" <?php selected(self::opt('mode','new_post'),'new_post'); ?>>出新文章（SEO 稿，達分先出版）</option>
            <option value="insert" <?php selected(self::opt('mode'),'insert'); ?>>插入現有舊文（niche edit，你逐個審批）</option>
          </select></td></tr>
          <tr><th>刊登方式（新文章）</th><td><select name="<?php echo self::OPT; ?>[post_status]">
            <option value="draft" <?php selected(self::opt('post_status','draft'),'draft'); ?>>先存草稿，我人手撳出版（建議）</option>
            <option value="publish" <?php selected(self::opt('post_status'),'publish'); ?>>SEO 分過咗門檻就直接刊登</option>
          </select></td></tr>
          <tr><th>Drip 延遲</th><td><label><input type="checkbox" name="<?php echo self::OPT; ?>[drip]" value="1" <?php checked(self::opt('drip',1)); ?>> 批准後隨機延遲 2–14 日先刊登（反 footprint，強烈建議開）</label></td></tr>
          <tr><th>Link rel 屬性</th><td><select name="<?php echo self::OPT; ?>[rel_mode]">
            <option value="auto" <?php selected(self::opt('rel_mode','auto'),'auto'); ?>>自動（付費提案用 sponsored，其餘 dofollow）</option>
            <option value="sponsored" <?php selected(self::opt('rel_mode'),'sponsored'); ?>>全部 sponsored（最保守）</option>
            <option value="dofollow" <?php selected(self::opt('rel_mode'),'dofollow'); ?>>全部 dofollow</option>
          </select></td></tr>
          <tr><th>SEO 分數門檻</th><td><input type="number" name="<?php echo self::OPT; ?>[min_score]" value="<?php echo esc_attr(self::opt('min_score',70)); ?>" min="0" max="100"> 分以下只入草稿</td></tr>
          <tr><th>分類</th><td><?php wp_dropdown_categories(['hide_empty'=>0,'name'=>self::OPT.'[category]','selected'=>self::opt('category',0),'show_option_none'=>'（預設）']); ?></td></tr>
          <tr><th colspan="2"><h2>AI 起稿（可選，BYOK）</h2><p class="description">填咗就用你嘅 OpenAI 兼容 API 寫全稿／寫插入句；留空就用模板。Key 只存喺你呢個站，唔會上傳。</p></th></tr>
          <tr><th>AI Endpoint</th><td><input name="<?php echo self::OPT; ?>[ai_endpoint]" value="<?php echo esc_attr(self::opt('ai_endpoint')); ?>" class="regular-text" placeholder="https://api.openai.com/v1/chat/completions"></td></tr>
          <tr><th>AI Model</th><td><input name="<?php echo self::OPT; ?>[ai_model]" value="<?php echo esc_attr(self::opt('ai_model')); ?>" placeholder="gpt-4o-mini"></td></tr>
          <tr><th>AI Key</th><td><input type="password" name="<?php echo self::OPT; ?>[ai_key]" value="" class="regular-text" autocomplete="new-password" placeholder="<?php echo self::opt('ai_key') ? '（已設定，留空唔改）' : 'sk-...'; ?>"></td></tr>
        </table>
        <?php submit_button(); ?>
        </form>
        <form method="post" style="margin-top:1em"><?php wp_nonce_field('bhp_poll_now'); ?>
          <input type="hidden" name="bhp_poll_now" value="1">
          <button class="button">即刻拉一次提案</button>
          下次自動輪詢：<?php echo wp_next_scheduled('bhp_poll') ? date('Y-m-d H:i', wp_next_scheduled('bhp_poll')) : '未排程'; ?>
        </form>
        <?php self::pendingTable(); self::placedTable(); ?>
        </div>
        <?php
        if (!empty($_POST['bhp_poll_now']) && check_admin_referer('bhp_poll_now')) self::poll();
    }

    // 待審插入隊列 UI
    private static function pendingTable() {
        $q = get_option(self::PENDING, []);
        if (!$q) return;
        echo '<h2>待你審批嘅插入（niche edit）</h2><table class="widefat striped" style="max-width:960px"><thead><tr><th>插入去邊篇</th><th>插入句預覽</th><th>目標</th><th>動作</th></tr></thead><tbody>';
        foreach ($q as $id => $p) {
            $post = get_post($p['postId']);
            if (!$post) continue;
            echo '<tr><td><a href="' . esc_url(get_edit_post_link($post->ID)) . '">' . esc_html($post->post_title) . '</a><br><small>' . esc_html(get_permalink($post->ID)) . '</small></td>';
            echo '<td>' . wp_kses_post($p['sentence']) . '</td>';
            echo '<td><small>' . esc_html($p['targetUrl']) . '<br>anchor: ' . esc_html($p['anchor']) . '</small></td>';
            echo '<td><form method="post" style="display:inline">' . wp_nonce_field('bhp_act', '_wpnonce', true, false) .
                 '<input type="hidden" name="bhp_approve" value="' . esc_attr($id) . '"><button class="button button-primary">批准（drip 刊登）</button></form> ' .
                 '<form method="post" style="display:inline">' . wp_nonce_field('bhp_act', '_wpnonce', true, false) .
                 '<input type="hidden" name="bhp_discard" value="' . esc_attr($id) . '"><button class="button">拒絕</button></form></td></tr>';
        }
        echo '</tbody></table>';
    }

    // 已刊登嘅 Hub 連結 + 一撳撤 link
    private static function placedTable() {
        $posts = get_posts(['post_type' => 'post', 'posts_per_page' => 50, 'post_status' => ['publish', 'draft'],
            'meta_query' => ['relation' => 'OR',
                ['key' => '_bhp_match', 'compare' => 'EXISTS'],
                ['key' => '_bhp_insert', 'compare' => 'EXISTS']]]);
        if (!$posts) return;
        echo '<h2>Hub 經手嘅連結（一撳撤 link）</h2><table class="widefat striped" style="max-width:960px"><thead><tr><th>文章</th><th>模式</th><th>Match</th><th>狀態</th><th>動作</th></tr></thead><tbody>';
        foreach ($posts as $p) {
            $ins = get_post_meta($p->ID, '_bhp_insert', true);
            $match = $ins ? $ins['matchId'] : get_post_meta($p->ID, '_bhp_match', true);
            echo '<tr><td><a href="' . esc_url(get_permalink($p->ID)) . '" target="_blank">' . esc_html($p->post_title) . '</a></td>';
            echo '<td>' . ($ins ? '插入舊文' : '新文章') . '</td><td><small>' . esc_html($match) . '</small></td>';
            echo '<td>' . esc_html($p->post_status) . '</td>';
            echo '<td><form method="post" onsubmit="return confirm(\'真係撤？會喺 Hub 標記做 removed。\')">' . wp_nonce_field('bhp_act', '_wpnonce', true, false) .
                 '<input type="hidden" name="bhp_remove" value="' . intval($p->ID) . '"><button class="button button-link-delete">撤 link</button></form></td></tr>';
        }
        echo '</tbody></table>';
    }

    private static function handleActions() {
        if (empty($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'bhp_act')) return;
        // 批准插入 → drip 排程
        if (!empty($_POST['bhp_approve'])) {
            $id = sanitize_text_field($_POST['bhp_approve']);
            $q = get_option(self::PENDING, []);
            if (isset($q[$id])) {
                $delay = self::opt('drip', 1) ? wp_rand(2, 14) * DAY_IN_SECONDS : 60;
                $q[$id]['scheduledAt'] = time() + $delay;
                update_option(self::PENDING, $q, false);
                wp_schedule_single_event(time() + $delay, 'bhp_do_insert', [$id]);
                self::flash('success', '已批准，' . round($delay / DAY_IN_SECONDS, 1) . ' 日後自動刊登（drip）。');
            }
        }
        // 拒絕插入
        if (!empty($_POST['bhp_discard'])) {
            $id = sanitize_text_field($_POST['bhp_discard']);
            $q = get_option(self::PENDING, []);
            if (isset($q[$id])) {
                self::api('POST', '/outcomes', ['matchId' => $q[$id]['matchId'], 'siteId' => $q[$id]['siteId'], 'outcome' => 'rejected']);
                unset($q[$id]);
                update_option(self::PENDING, $q, false);
                self::flash('info', '已拒絕，Hub 已記錄。');
            }
        }
        // 撤 link（insert 拆句 / 新文入垃圾桶）
        if (!empty($_POST['bhp_remove'])) {
            $postId = intval($_POST['bhp_remove']);
            $ins = get_post_meta($postId, '_bhp_insert', true);
            $matchId = $ins ? $ins['matchId'] : get_post_meta($postId, '_bhp_match', true);
            $siteId = $ins ? ($ins['siteId'] ?? null) : null;
            if ($ins && !empty($ins['sentence'])) {
                $post = get_post($postId);
                if ($post) {
                    wp_update_post(['ID' => $postId, 'post_content' => str_replace($ins['sentence'], '', $post->post_content)]);
                    delete_post_meta($postId, '_bhp_insert');
                }
            } else {
                wp_trash_post($postId);
            }
            if ($matchId) self::api('POST', '/outcomes', array_filter(['matchId' => $matchId, 'siteId' => $siteId, 'outcome' => 'removed']));
            self::flash('warning', '已撤 link，Hub 已標記 removed。');
        }
    }

    /* ────────────────────────── 通知 ────────────────────────── */

    public static function notices() {
        foreach ((array)get_transient('bhp_notices') as $n)
            printf('<div class="notice notice-%s"><p>Backlink Hub: %s</p></div>', esc_attr($n[0]), esc_html($n[1]));
        delete_transient('bhp_notices');
    }
    private static function flash($type, $msg) {
        $n = (array)get_transient('bhp_notices'); $n[] = [$type, $msg]; set_transient('bhp_notices', $n, 300);
    }

    private static function api($method, $path, $body = null) {
        $r = wp_remote_request(self::API . $path, [
            'method' => $method, 'timeout' => 30,
            'headers' => ['Authorization' => 'Bearer ' . self::opt('api_key'), 'Content-Type' => 'application/json'],
            'body' => $body ? wp_json_encode($body) : null,
        ]);
        if (is_wp_error($r)) return ['error' => $r->get_error_message()];
        return json_decode(wp_remote_retrieve_body($r), true) ?: [];
    }

    /* ────────────────────────── 輪詢 ────────────────────────── */

    public static function poll() {
        if (!self::opt('api_key')) return;
        $inbox = self::api('GET', '/publisher/inbox');
        $done = 0;
        foreach ((array)($inbox['items'] ?? []) as $it) {
            if ($it['status'] !== 'approved' || !empty($it['deliveredAt'])) continue;
            if ($done >= 3) break; // 防爆量：每次輪詢最多處理 3 個
            if (self::opt('mode', 'new_post') === 'insert') self::prepareInsert($it);
            else self::publish($it);
            $done++;
        }
    }

    /* ──────────────────── 模式一：插入舊文 ──────────────────── */

    private static function prepareInsert($it) {
        $kw = $it['keywords'][0] ?? '';
        $targetHost = parse_url($it['targetUrl'], PHP_URL_HOST);
        // 搵主題相關嘅舊文：關鍵字 search，要 21 日前出版，未插過呢個 domain
        $q = new WP_Query([
            's' => $kw, 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 15,
            'date_query' => [['before' => '21 days ago']],
        ]);
        $best = null; $bestScore = 0;
        foreach ($q->posts as $p) {
            if (get_post_meta($p->ID, '_bhp_insert', true)) continue;                    // 已插過 Hub link
            if ($targetHost && stripos($p->post_content, $targetHost) !== false) continue; // 已有呢個 domain 嘅 link
            $score = substr_count(strtolower($p->post_title), strtolower($kw)) * 3
                   + substr_count(strtolower(wp_strip_all_tags($p->post_content)), strtolower($kw));
            if ($score > $bestScore) { $bestScore = $score; $best = $p; }
        }
        if (!$best) { self::flash('warning', "提案 {$it['matchId']} 搵唔到合適舊文插入，跳過。"); return; }

        $anchor = self::rotateAnchor($it['anchorText'] ?: $kw, $it['targetUrl'], $kw);
        $rel = self::relAttr($it['paid']);
        $link = '<a href="' . esc_url($it['targetUrl']) . '"' . $rel . '>' . esc_html($anchor) . '</a>';
        $sentence = self::writeInsertSentence($it, $link, $best);

        $q2 = get_option(self::PENDING, []);
        $id = 'ins_' . wp_generate_password(10, false);
        $q2[$id] = [
            'matchId' => $it['matchId'], 'siteId' => $it['siteId'], 'postId' => $best->ID,
            'sentence' => $sentence, 'anchor' => $anchor, 'targetUrl' => $it['targetUrl'],
            'paid' => !empty($it['paid']), 'createdAt' => time(),
        ];
        update_option(self::PENDING, $q2, false);
        self::flash('info', "提案 {$it['matchId']} 已準備插入《{$best->post_title}》，等你喺 Backlink Hub 設定頁審批。");
        // 先 ack，唔好下次輪詢再重複整；outcome 等批准刊登先回報
        self::api('POST', '/publisher/inbox/ack', ['matchId' => $it['matchId'], 'siteId' => $it['siteId']]);
    }

    // drip 到點：真正寫入
    public static function doInsert($id) {
        $q = get_option(self::PENDING, []);
        if (empty($q[$id])) return;
        $p = $q[$id];
        $post = get_post($p['postId']);
        if (!$post || $post->post_status !== 'publish') { unset($q[$id]); update_option(self::PENDING, $q, false); return; }
        if (strpos($post->post_content, $p['sentence']) !== false) { unset($q[$id]); update_option(self::PENDING, $q, false); return; } // 已插

        // 揀位置：第 2–4 段之後（隨機，反 footprint）
        $parts = explode('</p>', $post->post_content);
        $maxPos = min(4, count($parts) - 1);
        $pos = $maxPos > 1 ? wp_rand(1, $maxPos) : 1;
        $newContent = implode('</p>', array_slice($parts, 0, $pos)) . '</p>' . "\n<p>" . $p['sentence'] . "</p>\n" . implode('</p>', array_slice($parts, $pos));
        wp_update_post(['ID' => $post->ID, 'post_content' => $newContent]); // WP 自動留 revision，可以還原

        update_post_meta($post->ID, '_bhp_insert', ['matchId' => $p['matchId'], 'siteId' => $p['siteId'], 'sentence' => "<p>{$p['sentence']}</p>", 'anchor' => $p['anchor']]);
        self::rememberAnchor($p['targetUrl'], $p['anchor']);
        self::api('POST', '/outcomes', [
            'matchId' => $p['matchId'], 'siteId' => $p['siteId'], 'outcome' => 'published',
            'liveUrl' => get_permalink($post->ID), 'paid' => $p['paid'],
            'mode' => 'insert', 'anchorUsed' => $p['anchor'],
        ]);
        unset($q[$id]);
        update_option(self::PENDING, $q, false);
        self::flash('success', "已插入 link 落《{$post->post_title}》並回報 Hub 驗證。");
    }

    /* ──────────────────── 模式二：出新文章 ──────────────────── */

    private static function publish($it) {
        $kw = $it['keywords'][0] ?? '';
        $anchor = self::rotateAnchor($it['anchorText'] ?: $kw, $it['targetUrl'], $kw);
        $rel = self::relAttr($it['paid']);
        $link = '<a href="' . esc_url($it['targetUrl']) . '"' . $rel . '>' . esc_html($anchor) . '</a>';

        $article = self::writeArticle($it, $link);
        $article['content'] = wp_kses_post($article['content']); // AI/模板內容過濾，防注入
        $article['title'] = sanitize_text_field($article['title']);
        $score = self::seoScore($article['title'], $article['content'], $kw);

        $canPublish = self::opt('post_status','draft') === 'publish' && $score['total'] >= intval(self::opt('min_score',70));
        $status = $canPublish ? 'publish' : 'draft';
        // drip：可以出版但開咗 drip → 先入草稿，排程遲啲自動出版
        if ($canPublish && self::opt('drip', 1)) $status = 'draft';
        $postId = wp_insert_post([
            'post_title' => $article['title'], 'post_content' => $article['content'],
            'post_status' => $status, 'post_category' => self::opt('category') ? [intval(self::opt('category'))] : [],
        ]);
        if (is_wp_error($postId) || !$postId) { self::flash('error', '出稿失敗：' . (is_wp_error($postId) ? $postId->get_error_message() : 'unknown')); return; }

        update_post_meta($postId, '_bhp_match', $it['matchId']);
        update_post_meta($postId, '_bhp_seo_score', $score['total']);
        update_post_meta($postId, '_bhp_anchor', $anchor);
        // Yoast / RankMath 兼容 meta description
        update_post_meta($postId, '_yoast_wpseo_metadesc', $article['meta']);
        update_post_meta($postId, 'rank_math_description', $article['meta']);

        if ($canPublish && self::opt('drip', 1)) {
            $delay = wp_rand(2, 14) * DAY_IN_SECONDS;
            wp_schedule_single_event(time() + $delay, 'bhp_do_publish', [$postId]);
            self::flash('info', "提案 {$it['matchId']} 已起稿（SEO {$score['total']} 分），drip：" . round($delay / DAY_IN_SECONDS, 1) . " 日後自動出版。");
        } else {
            $msg = "提案 {$it['matchId']} 已起稿（SEO {$score['total']} 分，{$status}）。" . ($score['total'] < intval(self::opt('min_score',70)) ? ' 未達門檻：' . implode('；', $score['gaps']) : '');
            self::flash($status === 'publish' ? 'success' : 'warning', $msg);
        }

        self::api('POST', '/publisher/inbox/ack', ['matchId' => $it['matchId'], 'siteId' => $it['siteId']]);
        if ($status === 'publish') { self::rememberAnchor($it['targetUrl'], $anchor); self::reportOutcome($postId, $it, $anchor); }
    }

    // drip 到點：自動出版草稿
    public static function doPublish($postId) {
        $post = get_post($postId);
        if (!$post || $post->post_status !== 'draft') return;
        wp_publish_post($postId); // 觸發 transition_post_status → onPublish 回報 Hub
    }

    private static function reportOutcome($postId, $it, $anchor = null) {
        if (get_post_meta($postId, '_bhp_reported', true)) return;
        self::api('POST', '/outcomes', array_filter([
            'matchId' => $it['matchId'], 'siteId' => $it['siteId'], 'outcome' => 'published',
            'liveUrl' => get_permalink($postId), 'paid' => $it['paid'],
            'mode' => 'new_post', 'anchorUsed' => $anchor,
        ]));
        update_post_meta($postId, '_bhp_reported', 1);
    }

    // 草稿之後人手撳出版：呢度接住回報 Hub（自動驗證 + 計分）
    public static function onPublish($new, $old, $post) {
        if ($new !== 'publish' || $old === 'publish') return;
        $matchId = get_post_meta($post->ID, '_bhp_match', true);
        if (!$matchId || get_post_meta($post->ID, '_bhp_reported', true)) return;
        $anchor = get_post_meta($post->ID, '_bhp_anchor', true);
        if ($anchor) self::rememberAnchor('', $anchor);
        self::api('POST', '/outcomes', array_filter(['matchId' => $matchId, 'outcome' => 'published', 'liveUrl' => get_permalink($post->ID), 'mode' => 'new_post', 'anchorUsed' => $anchor]));
        update_post_meta($post->ID, '_bhp_reported', 1);
    }

    /* ──────────────────── Anchor 輪換 + rel ──────────────────── */

    // 同一個 domain 唔好重複用同一條 anchor（反 footprint）
    private static function rotateAnchor($anchor, $targetUrl, $kw) {
        $host = parse_url($targetUrl, PHP_URL_HOST) ?: 'misc';
        $log = get_option(self::ANCHOR_LOG, []);
        $used = array_map('strtolower', (array)($log[$host] ?? []));
        if (!in_array(strtolower($anchor), $used)) return $anchor;
        foreach (array_filter([$kw, 'this ' . $kw . ' guide', 'learn more', $targetUrl, '詳情可以睇呢度']) as $alt) {
            if (!in_array(strtolower($alt), $used)) return $alt;
        }
        return $anchor . '（延伸閱讀）'; // 全部用晒就加後綴
    }
    private static function rememberAnchor($targetUrl, $anchor) {
        $host = parse_url($targetUrl, PHP_URL_HOST) ?: 'misc';
        $log = get_option(self::ANCHOR_LOG, []);
        $log[$host] = array_values(array_unique(array_merge((array)($log[$host] ?? []), [$anchor])));
        update_option(self::ANCHOR_LOG, $log, false);
    }
    private static function relAttr($paid) {
        $mode = self::opt('rel_mode', 'auto');
        if ($mode === 'sponsored') return ' rel="sponsored"';
        if ($mode === 'dofollow') return '';
        return $paid ? ' rel="sponsored"' : ''; // auto
    }

    /* ──────────────────── 寫文 / 寫插入句 ──────────────────── */

    private static function aiCall($prompt) {
        if (!self::opt('ai_endpoint') || !self::opt('ai_key') || !self::opt('ai_model')) return null;
        $r = wp_remote_post(self::opt('ai_endpoint'), ['timeout' => 90, 'headers' => [
            'Authorization' => 'Bearer ' . self::opt('ai_key'), 'Content-Type' => 'application/json'],
            'body' => wp_json_encode(['model' => self::opt('ai_model'), 'temperature' => 0.4,
                'messages' => [['role' => 'user', 'content' => $prompt]]])]);
        if (is_wp_error($r)) return null;
        $j = json_decode(wp_remote_retrieve_body($r), true);
        return $j['choices'][0]['message']['content'] ?? null;
    }

    // 插入句：6 個句式模板隨機（反 footprint）；有 AI 就用 AI 跟返原文語氣
    private static function writeInsertSentence($it, $link, $post) {
        $kw = $it['keywords'][0] ?? 'this topic';
        if ($ai = self::aiCall(
            "You are editing an existing blog post titled \"" . $post->post_title . "\". " .
            "Write ONE natural sentence (max 30 words, same language as the title) that fits the article's tone and includes this exact HTML link unchanged: {$link}. " .
            "Output only the sentence with the link, no quotes, no explanation."
        )) {
            $s = trim($ai);
            if (strpos($s, '<a ') !== false && str_word_count(wp_strip_all_tags($s)) <= 60) return $s;
        }
        $templates = [
            "If you're exploring {$kw}, {$link} is a practical place to start.",
            "For a deeper look at {$kw}, check out {$link}.",
            "Related reading: {$link} — a useful resource on {$kw}.",
            "We also recommend {$link} if {$kw} is on your radar.",
            "想深入了解 {$kw}，可以參考 {$link}。",
            "講到 {$kw}，{$link} 都幾值得一看。",
        ];
        return $templates[wp_rand(0, count($templates) - 1)];
    }

    private static function writeArticle($it, $link) {
        $kw = $it['keywords'][0] ?? 'this topic';
        $txt = self::aiCall(
            "Write a 700-word blog post about \"{$kw}\" for a " . ($it['niche'] ?? 'general') . " audience. " .
            "Naturally include this exact HTML link once, mid-article: {$link}. " .
            "Requirements: keyword in the title and first 100 words, at least two H2 subheadings, short paragraphs, end with a takeaway. " .
            "Return strict JSON: {\"title\":...,\"meta\":...(<=155 chars),\"content\":...(HTML with <h2>/<p>)}"
        );
        if ($txt) {
            $a = json_decode(trim(preg_replace('/^```json|```$/m', '', $txt)), true);
            if (!empty($a['content'])) return ['title' => $a['title'] ?: ucfirst($kw), 'meta' => $a['meta'] ?? '', 'content' => $a['content']];
        }
        // 無 AI：結構化模板稿（提案指示做主體）
        $guide = esc_html($it['instruction'] ?? '');
        $content = "<p>{$guide}</p>\n<p>When it comes to {$kw}, the details matter. " .
            "For a practical reference, see {$link}.</p>\n" .
            "<h2>Why {$kw} matters</h2>\n<p>…</p>\n<h2>Key takeaways</h2>\n<p>…</p>";
        return ['title' => ucfirst($kw) . ': a practical guide', 'meta' => substr("A practical guide to {$kw} — " . $guide, 0, 155), 'content' => $content];
    }

    // 10 項 SEO 檢查，每項 10 分
    public static function seoScore($title, $content, $kw) {
        $text = wp_strip_all_tags($content);
        $words = str_word_count($text);
        $kwl = strtolower($kw);
        $checks = [
            '標題含關鍵字'        => $kw && stripos($title, $kw) !== false,
            '首 100 字含關鍵字'   => $kw && stripos(substr($text, 0, 400), $kw) !== false,
            '字數 ≥600'          => $words >= 600,
            '有 H2 分段 ×2'      => substr_count(strtolower($content), '<h2') >= 2,
            '含目標連結'          => preg_match('/<a\b[^>]*href=/i', $content),
            '段落唔超長'          => !preg_match('/<p>(?:(?!<\/p>).){1200,}<\/p>/s', $content),
            '關鍵字密度 0.5–2.5%' => ($d = ($words && $kw ? substr_count(strtolower($text), $kwl) / max(1, str_word_count($kwl)) / $words * 100 : 0)) >= 0.3 && $d <= 3,
            'Meta description 齊' => true, // writeArticle 一定產生
            '標題長度 30–65'      => ($l = mb_strlen($title)) >= 20 && $l <= 70,
            '有總結段'            => stripos($content, 'takeaway') !== false || stripos($content, '總結') !== false || stripos($content, 'takeaways') !== false,
        ];
        $gaps = []; $total = 0;
        foreach ($checks as $name => $ok) { if ($ok) $total += 10; else $gaps[] = $name; }
        return ['total' => $total, 'gaps' => $gaps];
    }
}

BHP_Plugin::init();
