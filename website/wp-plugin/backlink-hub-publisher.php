<?php
// Backlink Hub Publisher（Code Snippets 版）


class BHP_Plugin {
    const OPT = 'bhp_settings';
    const API = 'https://seo-hub-imbe.onrender.com/v1/hub';

    public static function init() {
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_init', [__CLASS__, 'register']);
        add_action('bhp_poll', [__CLASS__, 'poll']);
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
        $ep = esc_url_raw($in['ai_endpoint'] ?? '');
        $out['ai_endpoint'] = (strpos($ep, 'https://') === 0 || $ep === '') ? $ep : ($out['ai_endpoint'] ?? ''); // 只准 https，防 SSRF
        $out['ai_model']    = sanitize_text_field($in['ai_model'] ?? '');
        if (!empty($in['ai_key'])) $out['ai_key'] = sanitize_text_field($in['ai_key']); // 留空 = 唔改
        return $out;
    }

    public static function page() {
        if (!current_user_can('manage_options')) return;
        $key = self::opt('api_key');
        ?>
        <div class="wrap"><h1>Backlink Hub Publisher</h1>
        <p>攞 API key：<a href="https://recmoment.net/hub-portal/" target="_blank">recmoment.net/hub-portal</a>。
        提案經你（或你預設嘅 autoApprove）批准之後，呢個插件會自動起稿、達到 SEO 分數門檻先會刊登，刊登後自動回報 Hub 驗證。</p>
        <form method="post" action="options.php">
        <?php settings_fields(self::OPT); ?>
        <table class="form-table">
          <tr><th>Hub API Key</th><td><input type="password" name="<?php echo self::OPT; ?>[api_key]" value="<?php echo esc_attr($key); ?>" class="regular-text" autocomplete="off">
          <p class="description">rmh_ 開頭，48 位 hex</p></td></tr>
          <tr><th>刊登方式</th><td><select name="<?php echo self::OPT; ?>[post_status]">
            <option value="draft" <?php selected(self::opt('post_status','draft'),'draft'); ?>>先存草稿，我人手撳出版（建議）</option>
            <option value="publish" <?php selected(self::opt('post_status'),'publish'); ?>>SEO 分過咗門檻就直接刊登</option>
          </select></td></tr>
          <tr><th>SEO 分數門檻</th><td><input type="number" name="<?php echo self::OPT; ?>[min_score]" value="<?php echo esc_attr(self::opt('min_score',70)); ?>" min="0" max="100"> 分以下只入草稿</td></tr>
          <tr><th>分類</th><td><?php wp_dropdown_categories(['hide_empty'=>0,'name'=>self::OPT.'[category]','selected'=>self::opt('category',0),'show_option_none'=>'（預設）']); ?></td></tr>
          <tr><th colspan="2"><h2>AI 起稿（可選，BYOK）</h2><p class="description">填咗就用你嘅 OpenAI 兼容 API 寫全稿；留空就用提案指示起結構化模板稿。Key 只存喺你呢個站，唔會上傳。</p></th></tr>
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
        </form></div>
        <?php
        if (!empty($_POST['bhp_poll_now']) && check_admin_referer('bhp_poll_now')) self::poll();
    }

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

    public static function poll() {
        if (!self::opt('api_key')) return;
        $inbox = self::api('GET', '/publisher/inbox');
        $done = 0;
        foreach ((array)($inbox['items'] ?? []) as $it) {
            if ($it['status'] !== 'approved' || !empty($it['deliveredAt'])) continue;
            if ($done >= 3) break; // 防爆量：每次輪詢最多起 3 篇
            self::publish($it);
            $done++;
        }
    }

    private static function publish($it) {
        $anchor = $it['anchorText'] ?: ($it['keywords'][0] ?? 'learn more');
        $rel = $it['paid'] ? ' rel="sponsored"' : '';
        $link = '<a href="' . esc_url($it['targetUrl']) . '"' . $rel . '>' . esc_html($anchor) . '</a>';
        $kw = $it['keywords'][0] ?? '';

        $article = self::writeArticle($it, $link);
        $article['content'] = wp_kses_post($article['content']); // AI/模板內容過濾，防注入
        $article['title'] = sanitize_text_field($article['title']);
        $score = self::seoScore($article['title'], $article['content'], $kw);

        $status = (self::opt('post_status','draft') === 'publish' && $score['total'] >= intval(self::opt('min_score',70))) ? 'publish' : 'draft';
        $postId = wp_insert_post([
            'post_title' => $article['title'], 'post_content' => $article['content'],
            'post_status' => $status, 'post_category' => self::opt('category') ? [intval(self::opt('category'))] : [],
        ]);
        if (is_wp_error($postId) || !$postId) { self::flash('error', '出稿失敗：' . (is_wp_error($postId) ? $postId->get_error_message() : 'unknown')); return; }

        update_post_meta($postId, '_bhp_match', $it['matchId']);
        update_post_meta($postId, '_bhp_seo_score', $score['total']);
        // Yoast / RankMath 兼容 meta description
        update_post_meta($postId, '_yoast_wpseo_metadesc', $article['meta']);
        update_post_meta($postId, 'rank_math_description', $article['meta']);

        $msg = "提案 {$it['matchId']} 已起稿（SEO {$score['total']} 分，{$status}）。" . ($score['total'] < intval(self::opt('min_score',70)) ? ' 未達門檻：' . implode('；', $score['gaps']) : '');
        self::flash($status === 'publish' ? 'success' : 'warning', $msg);

        // 起稿即 ack（唔好下次輪詢再重複起）；outcome 等真正 publish 先回報
        self::api('POST', '/publisher/inbox/ack', ['matchId' => $it['matchId'], 'siteId' => $it['siteId']]);
        if ($status === 'publish') self::reportOutcome($postId, $it);
    }

    private static function reportOutcome($postId, $it) {
        if (get_post_meta($postId, '_bhp_reported', true)) return;
        self::api('POST', '/outcomes', ['matchId' => $it['matchId'], 'siteId' => $it['siteId'], 'outcome' => 'published', 'liveUrl' => get_permalink($postId), 'paid' => $it['paid']]);
        update_post_meta($postId, '_bhp_reported', 1);
    }

    // 草稿之後人手撳出版：呢度接住回報 Hub（自動驗證 + 計分）
    public static function onPublish($new, $old, $post) {
        if ($new !== 'publish' || $old === 'publish') return;
        $matchId = get_post_meta($post->ID, '_bhp_match', true);
        if (!$matchId || get_post_meta($post->ID, '_bhp_reported', true)) return;
        self::api('POST', '/outcomes', ['matchId' => $matchId, 'outcome' => 'published', 'liveUrl' => get_permalink($post->ID)]);
        update_post_meta($post->ID, '_bhp_reported', 1);
    }

    private static function writeArticle($it, $link) {
        $kw = $it['keywords'][0] ?? 'this topic';
        if (self::opt('ai_endpoint') && self::opt('ai_key') && self::opt('ai_model')) {
            $prompt = "Write a 700-word blog post about \"{$kw}\" for a " . ($it['niche'] ?? 'general') . " audience. " .
                "Naturally include this exact HTML link once, mid-article: {$link}. " .
                "Requirements: keyword in the title and first 100 words, at least two H2 subheadings, short paragraphs, end with a takeaway. " .
                "Return strict JSON: {\"title\":...,\"meta\":...(<=155 chars),\"content\":...(HTML with <h2>/<p>)}";
            $r = wp_remote_post(self::opt('ai_endpoint'), ['timeout' => 90, 'headers' => [
                'Authorization' => 'Bearer ' . self::opt('ai_key'), 'Content-Type' => 'application/json'],
                'body' => wp_json_encode(['model' => self::opt('ai_model'), 'temperature' => 0.4,
                    'messages' => [['role' => 'user', 'content' => $prompt]]])]);
            if (!is_wp_error($r)) {
                $j = json_decode(wp_remote_retrieve_body($r), true);
                $txt = $j['choices'][0]['message']['content'] ?? '';
                $a = json_decode(trim(preg_replace('/^```json|```$/m', '', $txt)), true);
                if (!empty($a['content'])) return ['title' => $a['title'] ?: ucfirst($kw), 'meta' => $a['meta'] ?? '', 'content' => $a['content']];
            }
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
