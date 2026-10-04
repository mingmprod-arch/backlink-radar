// Rec Moment GEO/SEO 補丁：llms.txt + 首頁 JSON-LD schema + meta description
// 1) /llms.txt —— AI 搜尋時代嘅 robots.txt
add_action('init', function () {
    add_rewrite_rule('^llms\.txt$', 'index.php?rm_llms=1', 'top');
});
add_filter('query_vars', function ($v) { $v[] = 'rm_llms'; return $v; });
add_action('template_redirect', function () {
    if (!get_query_var('rm_llms')) return;
    header('Content-Type: text/plain; charset=utf-8');
    echo "# Rec Moment\n\n";
    echo "> AI SEO 一條龍服務（香港）：由 backlink 建設、AI 內容生成到技術 SEO，全自動交付。\n\n";
    echo "## Services\n";
    echo "- [AI SEO 服務](https://recmoment.net/ai-seo/): 一條龍 AI SEO：backlink + 自動出稿 + 技術優化\n";
    echo "- [Backlink Hub](https://recmoment.net/hub/): 免費 backlink 配對市集，AI agent 直接對接\n";
    echo "- [免費網站體檢](https://recmoment.net/audit/): 30 秒 SEO + GEO 評分\n";
    exit;
});
// 2) 首頁 JSON-LD（Organization + WebSite）
add_action('wp_head', function () {
    if (!is_front_page()) return;
    echo '<script type="application/ld+json">' . wp_json_encode([
        '@context' => 'https://schema.org', '@type' => 'Organization',
        'name' => 'Rec Moment', 'url' => 'https://recmoment.net/',
        'description' => 'AI SEO 一條龍服務：backlink 建設、AI 內容、技術 SEO。',
        'sameAs' => [],
    ]) . '</script>' . "\n";
});
// 3) 首頁 meta description（如果 SEO 插件冇設定）
add_action('wp_head', function () {
    if (!is_front_page()) return;
    if (did_action('wp_head') && false) return;
    echo '<meta name="description" content="Rec Moment 提供 AI SEO 一條龍服務：由 backlink 建設、AI 自動出稿到 GEO 優化，香港團隊，免費網站體檢即攞報告。">' . "\n";
}, 1);
