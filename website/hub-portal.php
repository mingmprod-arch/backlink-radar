
// ── Hub 會員 Portal（/hub-portal/）：terminal console 介面 + 中英切換 + onboarding checklist ──
function recmoment_hub_portal_shortcode() {
    ob_start(); ?>
    <div id="rmPortal" class="rmp">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap">
    <style>
      /* ── 成頁轉深色：Assembler theme header/footer 係透明底，靠 body 透色 ── */
      body.page-id-128{background:#0a0a0a!important}
      body.page-id-128 header.wp-block-template-part,
      body.page-id-128 footer.wp-block-template-part{background:#0a0a0a!important}
      body.page-id-128 header.wp-block-template-part{border-bottom:1px solid #1c1c1c}
      body.page-id-128 footer.wp-block-template-part{border-top:1px solid #1c1c1c}
      body.page-id-128 header.wp-block-template-part a,
      body.page-id-128 header.wp-block-template-part p,
      body.page-id-128 header.wp-block-template-part h1,
      body.page-id-128 header.wp-block-template-part h2,
      body.page-id-128 header.wp-block-template-part span{color:#c8c8c8!important}
      body.page-id-128 header.wp-block-template-part a:hover{color:#00d9a3!important}
      body.page-id-128 footer.wp-block-template-part,
      body.page-id-128 footer.wp-block-template-part a,
      body.page-id-128 footer.wp-block-template-part p,
      body.page-id-128 footer.wp-block-template-part h2,
      body.page-id-128 footer.wp-block-template-part h3,
      body.page-id-128 footer.wp-block-template-part li,
      body.page-id-128 footer.wp-block-template-part span{color:#666!important}
      body.page-id-128 .entry-content{padding-top:10px!important}
    </style>
    <style>
      .rmp{background:#0a0a0a;color:#d4d4d4;font-family:'JetBrains Mono',ui-monospace,SFMono-Regular,'SF Mono',Menlo,monospace;font-size:13px;line-height:1.65;border:1px solid #222;max-width:1080px;margin:0 auto}
      .rmp *{box-sizing:border-box}
      .rmp ::selection{background:#00d9a3;color:#000}
      .rmp code,.rmp .mono{font-family:inherit}
      .rmp a{color:#00d9a3;text-decoration:none}
      .rmp a:hover{text-decoration:underline}
      /* ── top bar ── */
      .rmp-top{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #222;padding:14px 24px}
      .rmp-brand{font-size:13px;font-weight:700;letter-spacing:.06em;color:#fff}
      .rmp-brand b{color:#00d9a3;font-weight:700}
      .rmp-topr{display:flex;align-items:center;gap:16px}
      .rmp-ver{font-size:11px;color:#555;letter-spacing:.08em}
      .rmp-lang{display:flex;border:1px solid #2a2a2a}
      .rmp-lang button{background:transparent;color:#777;border:0;padding:4px 12px;font-size:11px;font-family:inherit;cursor:pointer;letter-spacing:.05em}
      .rmp-lang button.on{background:#00d9a3;color:#000;font-weight:700}
      /* ── hero prompt ── */
      .rmp-hero{border-bottom:1px solid #222;padding:28px 24px 24px}
      .rmp-hline{font-size:14px}
      .rmp-pr{color:#00d9a3;font-weight:500}
      .rmp-cm{color:#666}
      .rmp-cmd{color:#fff;font-weight:500}
      .rmp-cur{display:inline-block;width:8px;height:15px;background:#00d9a3;vertical-align:-2px;margin-left:4px;animation:rmpblink 1.1s steps(1) infinite}
      @keyframes rmpblink{50%{opacity:0}}
      .rmp-hout{margin-top:10px;font-size:12px;color:#666;word-break:break-all}
      /* ── grid ── */
      .rmp-grid{display:grid;grid-template-columns:220px 1px 1fr}
      .rmp-grid>p{display:none}
      .rmp-vb{background:#1c1c1c}
      .rmp-side{padding:24px 20px;align-self:start;position:sticky;top:16px}
      .rmp-sblk{margin-bottom:28px}
      .rmp-lbl{font-size:10px;letter-spacing:.16em;color:#555;text-transform:uppercase;margin-bottom:10px}
      .rmp-srow{display:flex;justify-content:space-between;font-size:12px;padding:3px 0;gap:8px}
      .rmp-lbl2{color:#777}
      .rmp-val{color:#d4d4d4;text-align:right;word-break:break-all}
      .rmp-val.on{color:#00d9a3}
      .rmp-nv{display:block;font-size:12px;color:#888;padding:4px 0;text-decoration:none}
      .rmp-nv:hover{color:#00d9a3;text-decoration:none}
      .rmp-nv i{font-style:normal;color:#444;margin-right:8px}
      .rmp-main{padding:8px 28px 28px;min-width:0}
      /* ── sections（border-partitioned，唔係 card）── */
      .rmp-sec{border-top:1px solid #1c1c1c;padding:26px 0}
      .rmp-sec:first-child{border-top:0}
      .rmp-sec-h{display:flex;align-items:baseline;gap:10px;margin-bottom:16px}
      .rmp-sec-h .idx{color:#444;font-size:11px}
      .rmp-sec-h .ttl{font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:#999;font-weight:500}
      .rmp-sec-h .chip{margin-left:auto;font-size:10px;color:#555;border:1px solid #2a2a2a;padding:2px 8px;letter-spacing:.05em;white-space:nowrap}
      /* ── form ── */
      .rmp label{display:block;font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#555;margin:14px 0 5px}
      #rmPortal input[type="email"],#rmPortal input[type="text"],#rmPortal input[type="url"],#rmPortal input[type="number"],#rmPortal select{width:100%;background:#000!important;border:1px solid #262626!important;color:#d4d4d4!important;border-radius:0!important;padding:9px 12px!important;font-size:13px;font-family:inherit!important;box-shadow:none!important}
      #rmPortal input:focus,#rmPortal select:focus{outline:none;border-color:#00d9a3!important}
      #rmPortal input::placeholder{color:#3d3d3d}
      .rmp .btn{background:transparent;color:#00d9a3;border:1px solid #00d9a3;border-radius:0;padding:8px 18px;font-size:12px;font-family:inherit;font-weight:500;letter-spacing:.04em;cursor:pointer;margin-top:14px;display:inline-block}
      .rmp .btn:hover{background:#00d9a3;color:#000;text-decoration:none}
      .rmp .btn.ghost{color:#999;border-color:#333}
      .rmp .btn.ghost:hover{background:#1a1a1a;color:#fff;border-color:#555}
      .rmp .msg{display:none;margin-top:14px;padding:10px 12px;font-size:12px;word-break:break-all;border:1px solid}
      .rmp .msg.ok{display:block;border-color:#00d9a344;color:#00d9a3;background:rgba(0,217,163,.05)}
      .rmp .msg.err{display:block;border-color:#f8514944;color:#f85149;background:rgba(248,81,73,.05)}
      .rmp .hint{font-size:12px;color:#777;line-height:1.7}
      .rmp .hidden{display:none}
      /* ── stats ── */
      .rmp .stat{display:inline-block;padding:2px 28px 2px 0;margin:0 28px 8px 0;text-align:left;border:0}
      .rmp .stat+.stat{border-left:1px solid #222;padding-left:28px}
      .rmp .stat b{display:block;font-size:32px;font-weight:500;color:#fff;line-height:1.2}
      .rmp .stat.hot b{color:#00d9a3}
      .rmp .stat span{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#555}
      /* ── steps（terminal checklist）── */
      .rmp .step{display:flex;align-items:flex-start;gap:14px;padding:11px 0;border-bottom:1px solid #161616}
      .rmp .step:last-child{border-bottom:0}
      .rmp .step .n{color:#555;font-size:12px;flex:none;width:34px;padding-top:1px}
      .rmp .step.done .n{color:#00d9a3}
      .rmp .step .t{font-size:13px;color:#e8e8e8;font-weight:500}
      .rmp .step.done .t{color:#00d9a3}
      .rmp .step .d{font-size:12px;color:#777;margin-top:2px;line-height:1.6}
      .rmp .step .act{margin-left:auto;flex:none}
      .rmp .step .act .btn{margin-top:0;padding:5px 14px;font-size:11px}
      /* ── site results ── */
      .rmp .sitecard{border:1px solid #1c1c1c;border-left:2px solid #2a2a2a;padding:10px 14px;margin-top:8px;font-size:12px}
      .rmp .sitecard:hover{border-left-color:#00d9a3}
      .rmp .sitecard b{color:#e8e8e8;font-weight:500;word-break:break-all}
      .rmp .sitecard .meta{color:#666;font-size:11px;margin-top:3px}
      /* ── runs table ── */
      .rmp table.runs{width:100%;border-collapse:collapse;font-size:12px}
      .rmp table.runs th{text-align:left;color:#555;font-weight:500;font-size:10px;text-transform:uppercase;letter-spacing:.12em;padding:6px 10px;border-bottom:1px solid #222}
      .rmp table.runs td{padding:9px 10px;border-bottom:1px solid #161616;color:#c8c8c8;vertical-align:top}
      .rmp table.runs tr:hover td{background:#101010}
      .rmp .pill{display:inline-block;font-size:10px;letter-spacing:.06em;text-transform:uppercase;padding:1px 8px;border:1px solid;font-weight:500}
      .rmp .pill.ok{color:#00d9a3;border-color:#00d9a355}
      .rmp .pill.wait{color:#d29922;border-color:#d2992255}
      .rmp .pill.no{color:#f85149;border-color:#f8514955}
      /* ── footer ── */
      .rmp-foot{border-top:1px solid #222;padding:14px 24px;font-size:10px;letter-spacing:.1em;color:#444;text-transform:uppercase;display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px}
      /* ── theme footer: match dark terminal ── */
      body.page-id-128 footer.wp-block-template-part .is-style-section-1{background:#060606!important}
      body.page-id-128 footer.wp-block-template-part h3{color:#8b949e!important;font-size:13px!important;letter-spacing:.12em!important;text-transform:uppercase!important}
      body.page-id-128 footer.wp-block-template-part p{color:#555!important;font-size:13px!important}
      body.page-id-128 footer.wp-block-template-part a{color:#3ddc97!important;text-decoration:none!important}
      body.page-id-128 footer.wp-block-template-part a:hover{color:#7dffc8!important}
      @media (max-width:900px){
        .rmp-grid{grid-template-columns:1fr}
        .rmp-vb{display:none}
        .rmp-side{position:static;border-bottom:1px solid #222;padding:16px 24px}
        .rmp-main{padding:8px 20px 20px}
        .rmp .stat+.stat{padding-left:16px;margin-left:0}
      }
    </style>

    <div class="rmp-top">
      <div class="rmp-brand">RECMOMENT<b>://</b>BACKLINK-HUB</div>
      <div class="rmp-topr">
        <span class="rmp-ver">console v2.0</span>
        <div class="rmp-lang">
          <button id="rmLangZh" class="on" onclick="rmSetLang('zh')">中文</button>
          <button id="rmLangEn" onclick="rmSetLang('en')">EN</button>
        </div>
      </div>
    </div>

    <div class="rmp-hero">
      <div class="rmp-hline"><span class="rmp-pr">guest@recmoment</span><span class="rmp-cm">:~$</span> <span class="rmp-cmd">hub status --live</span><span class="rmp-cur"></span></div>
      <div class="rmp-hout" id="rmHeroOut" data-i18n="heroOff">// 未連接 — 下面免費 join 或貼 key 開 session</div>
    </div>

    <div class="rmp-grid">
      <aside class="rmp-side">
        <div class="rmp-sblk">
          <div class="rmp-lbl" data-i18n="sess">工作階段</div>
          <div class="rmp-srow"><span class="rmp-lbl2" data-i18n="sstat">狀態</span><span class="rmp-val" id="rmSesStat" data-i18n="discon">未連接</span></div>
          <div class="rmp-srow"><span class="rmp-lbl2" data-i18n="splan">計劃</span><span class="rmp-val" id="rmSesPlan">–</span></div>
          <div class="rmp-srow"><span class="rmp-lbl2">key</span><span class="rmp-val mono" id="rmSesKey">–</span></div>
        </div>
        <div class="rmp-sblk">
          <div class="rmp-lbl" data-i18n="nav">目錄</div>
          <a class="rmp-nv" href="#portal-join"><i>01</i>credentials</a><a class="rmp-nv" href="#portal-onboard"><i>02</i>quickstart</a><a class="rmp-nv" href="#portal-dash"><i>03</i>account</a><a class="rmp-nv" href="#portal-submit"><i>04</i>submit-site</a><a class="rmp-nv" href="#portal-wish"><i>05</i>wishlist</a><a class="rmp-nv" href="#portal-runs"><i>06</i>runs</a><a class="rmp-nv" href="#portal-upgrade"><i>07</i>upgrade</a>
        </div>
      </aside>
      <div class="rmp-vb"></div>
      <div class="rmp-main">
    <div class="rmp-sec" id="portal-join">
      <div class="rmp-sec-h"><span class="idx">// 01</span><span class="ttl" data-i18n="t1">取得 API key（免費）</span><span class="chip">POST /hub/public/signup</span></div>
      <div>
        <p class="hint" data-i18n="d1">免費計劃：目錄查詢每月 20 次。之後用積分解鎖配對——交站賺分（+0.5/DR）、成交驗證 +15。</p>
        <label>email</label>
        <input type="email" id="rmEmail" placeholder="you@example.com">
        <button class="btn" onclick="rmSignup()" data-i18n="b1">$ join --free</button>
        <p class="hint" style="margin-top:18px" data-i18n="d1b">已經有 key？直接連接：</p>
        <input type="text" id="rmKey" placeholder="rmh_...">
        <button class="btn ghost" onclick="rmSaveKey()" data-i18n="b1b">connect</button>
        <div class="msg" id="rmJoinMsg"></div>
      </div>
    </div>

    <div class="rmp-sec hidden" id="portal-onboard">
      <div class="rmp-sec-h"><span class="idx">// 02</span><span class="ttl" data-i18n="t0">快速上手：3 步出第一個 match</span><span class="chip">getting-started</span></div>
      <div>
        <div id="rmSteps"></div>
        <div id="rmTryOut"></div>
      </div>
    </div>

    <div class="rmp-sec hidden" id="portal-dash">
      <div class="rmp-sec-h"><span class="idx">// 03</span><span class="ttl" data-i18n="t2">帳戶狀態</span><span class="chip">GET /v1/hub/me</span></div>
      <div>
        <div class="stat hot"><b id="rmPoints">–</b><span data-i18n="s1">積分結餘</span></div>
        <div class="stat"><b id="rmMatches">–</b><span>matches = 20 pts</span></div>
        <div class="stat"><b id="rmQuota">–</b><span data-i18n="s2">本月查詢</span></div>
        <p class="hint" style="margin-top:14px"><span data-i18n="ref">推薦碼</span>: <code id="rmRef" class="mono">–</code></p>
        <p class="hint" id="rmHist"></p>
      </div>
    </div>

    <div class="rmp-sec hidden" id="portal-submit">
      <div class="rmp-sec-h"><span class="idx">// 04</span><span class="ttl" data-i18n="t3">提交網站賺分</span><span class="chip">POST /v1/hub/sites</span></div>
      <div>
        <p class="hint" data-i18n="d3">交一個你擁有、肯收投稿嘅站。DR 越高分越多（封頂 50）。逐站審核，PBN 拒收，同一 domain 全網只計一次分。</p>
        <label>url</label><input type="url" id="rmSiteUrl" placeholder="https://yourblog.com">
        <label>niche</label><input type="text" id="rmSiteNiche" placeholder="seo / marketing / tech">
        <label>accepts</label>
        <select id="rmSiteAccepts"><option value="guest_post">guest_post</option><option value="resource_page">resource_page</option><option value="niche_edit">niche_edit</option></select>
        <label>dr</label><input type="number" id="rmSiteDr" placeholder="40" min="0" max="100">
        <button class="btn" onclick="rmSubmitSite()" data-i18n="b3">$ submit --earn</button>
        <div class="msg" id="rmSiteMsg"></div>
      </div>
    </div>

    <div class="rmp-sec hidden" id="portal-wish">
      <div class="rmp-sec-h"><span class="idx">// 05</span><span class="ttl" data-i18n="t4">Wishlist（出版方）</span><span class="chip">POST /v1/hub/wishlist</span></div>
      <div>
        <p class="hint" data-i18n="d4">話俾買家知你而家想收咩題——撞中 wishlist 嘅配對請求 +3 分排前。</p>
        <label>site_id</label><input type="text" id="rmWishSite" placeholder="site_...">
        <label data-i18n="l4">想收嘅題（逗號分隔）</label><input type="text" id="rmWishTopics" placeholder="ai seo tools, link building">
        <button class="btn" onclick="rmSetWishlist()" data-i18n="b4">$ wishlist --set</button>
        <div class="msg" id="rmWishMsg"></div>
      </div>
    </div>

    <div class="rmp-sec hidden" id="portal-runs">
      <div class="rmp-sec-h"><span class="idx">// 06</span><span class="ttl" data-i18n="t5">Runs（配對紀錄）</span><span class="chip">GET /v1/hub/runs</span>
        <span style="margin-left:auto;display:flex;gap:6px">
          <button class="btn ghost" style="margin-top:0;padding:3px 12px;font-size:10px" id="rmRunsViewBtn" onclick="rmToggleRunsView()">JSON</button>
          <button class="btn ghost" style="margin-top:0;padding:3px 12px;font-size:10px" onclick="rmExportRuns()">Export</button>
        </span>
      </div>
      <div>
        <div id="rmRunsTable"></div>
        <pre id="rmRunsJson" class="hidden mono" style="background:#000;border:1px solid #222;padding:14px;font-size:11px;overflow:auto;max-height:400px;color:#c8c8c8"></pre>
      </div>
    </div>

    <div class="rmp-sec hidden" id="portal-upgrade">
      <div class="rmp-sec-h"><span class="idx">// 07</span><span class="ttl" data-i18n="t6">升級解鎖</span><span class="chip">stripe</span></div>
      <div>
        <p class="hint" data-i18n="d6">Member $19 一次性：100 次/月查詢。Pro $49/月：無限查詢 + 自動配對。Agency $199/月：25 站 + 白標報告。500 積分 $29 一次性：唔想訂閱就買分。付款後 plan 自動升級。</p>
        <a class="btn ghost" style="margin-right:8px" href="https://buy.stripe.com/dRm00c6BzcF563k2FC1Jm01" target="_blank">Member $19</a><a class="btn" style="margin-right:8px" href="https://buy.stripe.com/fZuaEQe41eNd9fwa841Jm02" target="_blank">Pro $49/月</a><a class="btn ghost" style="margin-right:8px" href="https://buy.stripe.com/5kQ5kw6BzawX77ocgc1Jm03" target="_blank">Agency $199/月</a><a class="btn ghost" href="https://buy.stripe.com/3cI28kaRPawX2R8gws1Jm04" target="_blank">500 pts / $29</a>
      </div>
    </div>

      </div>
    </div>

    <div class="rmp-foot">
      <span>22.3193°N 114.1694°E · HONG KONG · hub.recmoment.net</span>
      <span data-i18n="foot">唔賣 link · 出版方人工審批 · 付費 placement 一定 sponsored</span>
    </div>
    </div>

    <script>
    (function(){
      var API='https://hub.recmoment.net';
      var I18N={
        zh:{t1:'取得 API key（免費）',d1:'免費計劃：目錄查詢每月 20 次。之後用積分解鎖配對——交站賺分（+0.5/DR）、成交驗證 +15。',b1:'$ join --free',d1b:'已經有 key？直接連接：',b1b:'connect',t2:'帳戶狀態',s1:'積分結餘',s2:'本月查詢',ref:'推薦碼',t3:'提交網站賺分',d3:'交一個你擁有、肯收投稿嘅站。DR 越高分越多（封頂 50）。逐站審核，PBN 拒收，同一 domain 全網只計一次分。',b3:'$ submit --earn',t4:'Wishlist（出版方）',d4:'話俾買家知你而家想收咩題——撞中 wishlist 嘅配對請求 +3 分排前。',l4:'想收嘅題（逗號分隔）',b4:'$ wishlist --set',
          neterr:'網絡錯誤，稍後再試',keybad:'key 格式唔啱（rmh_ 開頭 48 位 hex）',linked:'✓ 已連接',keyonce:'✓ 你嘅 key（只顯示一次，請即抄低）：',subok:'✓ 已提交，賺咗 ',pts:' 分；審核後上架。',wishok:'✓ 已更新：',recent:'最近：',t5:'Runs（配對紀錄）',noruns:'仲未有配對紀錄。用 match 工具發起第一次配對啦。',t6:'升級解鎖',d6:'Member $19 一次性：100 次/月查詢。Pro $49/月：無限查詢 + 自動配對。Agency $199/月：25 站 + 白標報告。500 積分 $29 一次性：唔想訂閱就買分。付款後 plan 自動升級。',copied:'✓ 已複製',paid:'✓ 付款成功！多謝支持。升級會喺 24 小時內生效，請用付款 email 喺下面 connect 你嘅 key。',
          t0:'快速上手：3 步出第一個 match',st1t:'取得 API key',st1d:'免費帳戶即開即用，key 只顯示一次。',st2t:'駁你嘅 AI agent',st2d:'一撳複製 MCP 設定 + 示範指令，貼落 Claude / Cursor / Kimi 即用。',st3t:'跑第一次目錄查詢',st3d:'唔使寫 code——撳掣即場試 API，睇返已審核出版站。',st4t:'發第一個配對',st4d:'喺你嘅 agent 講：用 hub_request_match 幫我搵出版方。成交驗證雙方 +15 分。',copyai:'Copy for AI',tryit:'$ search --limit 3',trywait:'跑緊…',tryusage:'本月用量',
          sess:'工作階段',sstat:'狀態',splan:'計劃',discon:'未連接',con:'已連接',nav:'目錄',heroOff:'// 未連接 — 下面免費 join 或貼 key 開 session',foot:'唔賣 link · 出版方人工審批 · 付費 placement 一定 sponsored'},
        en:{t1:'Get your API key (free)',d1:'Free plan: 20 directory queries/month. Unlock matches with points — submit sites (+0.5/DR), verified outcomes +15.',b1:'$ join --free',d1b:'Already have a key? Connect it:',b1b:'connect',t2:'Account',s1:'points balance',s2:'queries this month',ref:'Referral link',t3:'Submit a site, earn points',d3:'Submit a site you own that accepts contributions. Higher DR earns more (cap 50). Every site is vetted — PBNs rejected, one point grant per domain network-wide.',b3:'$ submit --earn',t4:'Wishlist (publishers)',d4:'Tell buyers what topics you want right now — matches hitting your wishlist score +3.',l4:'Wanted topics (comma-separated)',b4:'$ wishlist --set',
          neterr:'Network error, try again later',keybad:'Invalid key format (rmh_ + 48 hex)',linked:'✓ Connected',keyonce:'✓ Your key (shown once — save it now): ',subok:'✓ Submitted, earned ',pts:' pts; listed after review.',wishok:'✓ Updated: ',recent:'latest: ',t5:'Runs (match history)',noruns:'No runs yet — fire your first match request to get going.',t6:'Upgrade',d6:'Member $19 one-time: 100 queries/mo. Pro $49/mo: unlimited queries + auto-match. Agency $199/mo: 25 sites + white-label reports. 500 points for $29 one-time — no subscription needed. Plan upgrades automatically after payment.',copied:'✓ Copied',paid:'✓ Payment received — thank you! Your upgrade activates within 24 hours. Connect your key below with the email you paid with.',
          t0:'Quickstart: your first match in 3 steps',st1t:'Get your API key',st1d:'Free account, instant — the key is shown once.',st2t:'Connect your AI agent',st2d:'One click copies the MCP config + sample prompts. Paste into Claude / Cursor / Kimi and go.',st3t:'Run your first directory search',st3d:'No code needed — try the API right here and see the vetted publisher sites.',st4t:'Fire your first match',st4d:'Tell your agent: use hub_request_match to find publishers for me. Verified outcomes earn both sides +15 pts.',copyai:'Copy for AI',tryit:'$ search --limit 3',trywait:'running…',tryusage:'usage this month',
          sess:'SESSION',sstat:'status',splan:'plan',discon:'not connected',con:'connected',nav:'INDEX',heroOff:'// not connected — join free below or paste your key to open a session',foot:'no link selling · human-approved placements · paid = always sponsored'}
      };
      window.rmSetLang=function(l){
        var d=I18N[l]||I18N.zh;
        document.querySelectorAll('#rmPortal [data-i18n]').forEach(function(e){var k=e.getAttribute('data-i18n');if(d[k])e.textContent=d[k]});
        document.getElementById('rmLangZh').className=l==='zh'?'on':'';
        document.getElementById('rmLangEn').className=l==='en'?'on':'';
        localStorage.setItem('rmHubLang',l);
        rmRenderOnboard();
        rmHero();
      };
      function lang(){return localStorage.getItem('rmHubLang')||'zh'}
      function T(k){return (I18N[lang()]||I18N.zh)[k]||k}
      function key(){return localStorage.getItem('rmHubKey')||''}
      function msg(id,text,ok){var e=document.getElementById(id);e.textContent=text;e.className='msg '+(ok?'ok':'err')}
      function authed(){return {'content-type':'application/json','authorization':'Bearer '+key()}}
      function rmEl(tag,cls,text){var e=document.createElement(tag);if(cls)e.className=cls;if(text!=null)e.textContent=text;return e}
      // ── hero 狀態行 + sidebar session ──
      window.rmDash=null;
      window.rmHero=function(){
        var out=document.getElementById('rmHeroOut');
        if(window.rmDash){
          var d=window.rmDash;
          out.textContent='plan='+d.plan+' · points='+d.balance+' · queries='+d.queries+' · matches='+Math.floor(d.balance/20);
          out.style.color='#00d9a3';
        }else{
          out.textContent=T('heroOff');
          out.style.color='';
        }
      };
      function rmSetSession(me){
        var st=document.getElementById('rmSesStat');
        st.textContent=T('con');st.classList.add('on');st.removeAttribute('data-i18n');
        document.getElementById('rmSesPlan').textContent=(me&&me.plan)||'free';
        var k=key();
        document.getElementById('rmSesKey').textContent=k?(k.slice(0,8)+'…'+k.slice(-4)):'–';
      }
      window.rmSignup=function(){
        var email=document.getElementById('rmEmail').value.trim();
        fetch(API+'/hub/public/signup',{method:'POST',headers:{'content-type':'application/json'},body:JSON.stringify({email:email})})
        .then(function(r){return r.json()}).then(function(d){
          if(d.apiKey){localStorage.setItem('rmHubKey',d.apiKey);msg('rmJoinMsg',T('keyonce')+d.apiKey,true);
            var cp=document.createElement('button');cp.className='btn ghost';cp.style.marginLeft='8px';cp.textContent='copy';
            cp.onclick=function(){navigator.clipboard.writeText(d.apiKey).then(function(){cp.textContent=T('copied')})};
            document.getElementById('rmJoinMsg').appendChild(cp);rmLoadDash();}
          else msg('rmJoinMsg',d.message||d.error||'error',!!d.already);
        }).catch(function(){msg('rmJoinMsg',T('neterr'),false)});
      };
      window.rmSaveKey=function(){
        var k=document.getElementById('rmKey').value.trim();
        if(!/^rmh_[a-f0-9]{48}$/i.test(k))return msg('rmJoinMsg',T('keybad'),false);
        localStorage.setItem('rmHubKey',k);msg('rmJoinMsg',T('linked'),true);rmLoadDash();
      };
      // ── onboarding checklist（terminal [x] 風）──
      window.rmRenderOnboard=function(){
        var box=document.getElementById('rmSteps');if(!box)return;
        box.textContent='';
        var hasKey=!!key();
        var copiedAI=localStorage.getItem('rmHubCopiedAI')==='1';
        var triedSearch=localStorage.getItem('rmHubFirstSearch')==='1';
        var hasRun=(window.rmRunsData&&(window.rmRunsData.total||0)>0);
        var steps=[
          {done:hasKey,t:T('st1t'),d:T('st1d'),act:null},
          {done:copiedAI,t:T('st2t'),d:T('st2d'),act:'copyai'},
          {done:triedSearch,t:T('st3t'),d:T('st3d'),act:'tryit'},
          {done:hasRun,t:T('st4t'),d:T('st4d'),act:null}
        ];
        steps.forEach(function(s,i){
          var row=rmEl('div','step'+(s.done?' done':''));
          row.appendChild(rmEl('div','n',s.done?'[✓]':('['+(i+1)+']')));
          var mid=rmEl('div');
          mid.appendChild(rmEl('div','t',s.t));
          mid.appendChild(rmEl('div','d',s.d));
          row.appendChild(mid);
          if(s.act==='copyai'&&hasKey){
            var b=rmEl('button','btn ghost',T('copyai'));
            b.onclick=rmCopyForAI;var sp=rmEl('span','act');sp.appendChild(b);row.appendChild(sp);
          }
          if(s.act==='tryit'&&hasKey){
            var b2=rmEl('button','btn',T('tryit'));
            b2.onclick=rmTrySearch;var sp2=rmEl('span','act');sp2.appendChild(b2);row.appendChild(sp2);
          }
          box.appendChild(row);
        });
      };
      window.rmCopyForAI=function(){
        var k=key();
        var txt='Connect your AI agent to the Backlink Hub MCP server:\n\n'
          +'{\n  "mcpServers": {\n    "backlink-hub": {\n      "type": "http",\n'
          +'      "url": "https://hub.recmoment.net/mcp",\n'
          +'      "headers": { "Authorization": "Bearer '+k+'" }\n    }\n  }\n}\n\n'
          +'Then ask your agent:\n'
          +'1. "Use hub_my_account to show my plan, points and query usage."\n'
          +'2. "Use hub_search_sites to list vetted seo blogs that accept guest posts."\n'
          +'3. "Use hub_analyze_content on my draft at https://yourblog.com/post and find matching publishers."\n'
          +'4. "Use hub_request_match with my target URL and keywords to draft placement proposals."';
        navigator.clipboard.writeText(txt).then(function(){
          localStorage.setItem('rmHubCopiedAI','1');rmRenderOnboard();
        });
      };
      window.rmTrySearch=function(){
        var out=document.getElementById('rmTryOut');
        out.textContent=T('trywait');
        fetch(API+'/v1/hub/sites?limit=3',{headers:authed()})
        .then(function(r){return r.json()}).then(function(d){
          out.textContent='';
          if(d.error){out.appendChild(rmEl('div','hint',d.error));return}
          (d.sites||[]).forEach(function(s){
            var c=rmEl('div','sitecard');
            c.appendChild(rmEl('b',null,s.url));
            var meta='niche: '+s.niche+'  ·  accepts: '+(s.accepts||[]).join('/')+'  ·  DR: '+(s.dr==null?'—':s.dr)
              +'  ·  response rate: '+(s.responseRate==null?'new':Math.round(s.responseRate*100)+'%');
            if(s.wishlist&&s.wishlist.length)meta+='  ·  wants: '+s.wishlist.slice(0,3).join(', ');
            c.appendChild(rmEl('div','meta',meta));
            out.appendChild(c);
          });
          if(d.usage&&d.usage!=='unlimited'){
            out.appendChild(rmEl('p','hint',T('tryusage')+': '+d.usage.usedThisMonth+' / '+d.usage.monthlyAllowance+(d.usage.bonusQueriesLeft?' (+'+d.usage.bonusQueriesLeft+' bonus)':'')));
          }
          localStorage.setItem('rmHubFirstSearch','1');rmRenderOnboard();
        }).catch(function(){out.textContent=T('neterr')});
      };
      window.rmLoadDash=function(){
        fetch(API+'/v1/hub/points',{headers:authed()}).then(function(r){if(!r.ok)throw 0;return r.json()})
        .then(function(d){
          document.getElementById('rmPoints').textContent=d.balance;
          document.getElementById('rmMatches').textContent=Math.floor(d.balance/20);
          document.getElementById('rmHist').textContent=d.history.length?(T('recent')+d.history[0].reason):'';
          window.rmDash={balance:d.balance,plan:'free',queries:'–'};
          fetch(API+'/v1/hub/me',{headers:authed()}).then(function(r){return r.json()})
          .then(function(me){
            var q=me.directoryQueries;
            var qs=(q==='unlimited')?'∞':(q.usedThisMonth+'/'+q.monthlyAllowance);
            document.getElementById('rmQuota').textContent=qs;
            window.rmDash.plan=me.plan||'free';
            window.rmDash.queries=qs;
            rmSetSession(me);
            rmHero();
          }).catch(function(){});
          fetch(API+'/v1/hub/referral-code',{headers:authed()}).then(function(r){return r.json()})
          .then(function(r2){document.getElementById('rmRef').textContent=r2.shareUrl||r2.code});
          ['portal-onboard','portal-dash','portal-submit','portal-wish','portal-runs','portal-upgrade'].forEach(function(id){document.getElementById(id).classList.remove('hidden')});
          rmRenderOnboard();
          rmLoadRuns();
        }).catch(function(){});
      };
      // ── Runs：DOM 構建（避免 wpautop 吃掉 block 標籤字串）──
      var rmRunsJsonMode=false;
      window.rmRunsData=null;
      function rmPillEl(status){
        var cls=/published|approved|verified/.test(status)?'pill ok':(/reject|fail|no_reply/.test(status)?'pill no':'pill wait');
        return rmEl('span',cls,status);
      }
      function rmRenderRuns(){
        var box=document.getElementById('rmRunsTable'),pre=document.getElementById('rmRunsJson');
        box.textContent='';
        if(rmRunsJsonMode){
          pre.classList.remove('hidden');
          pre.textContent=JSON.stringify(window.rmRunsData,null,2);
          document.getElementById('rmRunsViewBtn').textContent='Table';return;
        }
        pre.classList.add('hidden');document.getElementById('rmRunsViewBtn').textContent='JSON';
        var runs=(window.rmRunsData&&window.rmRunsData.runs)||[];
        if(!runs.length){box.appendChild(rmEl('div','hint',T('noruns')));return}
        var tb=rmEl('table','runs'),tr=rmEl('tr');
        ['run','role','target','keywords','status','outcome','created'].forEach(function(c){tr.appendChild(rmEl('th',null,c))});
        tb.appendChild(tr);
        runs.forEach(function(r){
          var row=rmEl('tr');
          row.appendChild(rmEl('td','mono',r.id.replace('match_','')));
          row.appendChild(rmEl('td',null,r.role));
          var tdT=rmEl('td');tdT.style.cssText='max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap';tdT.textContent=r.targetUrl;row.appendChild(tdT);
          row.appendChild(rmEl('td',null,(r.keywords||[]).join(', ')));
          var tdS=rmEl('td');r.proposals.forEach(function(p){tdS.appendChild(rmPillEl(p.status));tdS.appendChild(document.createTextNode(' '))});row.appendChild(tdS);
          var tdO=rmEl('td');
          if(r.outcomes.length){r.outcomes.forEach(function(o,i){
            if(i)tdO.appendChild(rmEl('br'));
            var t=o.outcome+(o.verified?' ✓verified':'');
            if(o.liveUrl){var a=rmEl('a',null,t);a.href=o.liveUrl;a.target='_blank';tdO.appendChild(a)}
            else tdO.appendChild(document.createTextNode(t));
          })}else{var s=rmEl('span',null,'—');s.style.color='#555';tdO.appendChild(s)}
          row.appendChild(tdO);
          var tdD=rmEl('td','mono',(r.createdAt||'').slice(0,10));tdD.style.color='#555';row.appendChild(tdD);
          tb.appendChild(row);
        });
        box.appendChild(tb);
      }
      window.rmLoadRuns=function(){
        fetch(API+'/v1/hub/runs',{headers:authed()}).then(function(r){return r.json()})
        .then(function(d){window.rmRunsData=d;rmRenderRuns();rmRenderOnboard()}).catch(function(){});
      };
      window.rmToggleRunsView=function(){rmRunsJsonMode=!rmRunsJsonMode;rmRenderRuns()};
      window.rmExportRuns=function(){
        var blob=new Blob([JSON.stringify(window.rmRunsData,null,2)],{type:'application/json'});
        var a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='backlink-hub-runs.json';a.click();
      };
      window.rmSubmitSite=function(){
        var body={url:document.getElementById('rmSiteUrl').value,niche:document.getElementById('rmSiteNiche').value,
          accepts:[document.getElementById('rmSiteAccepts').value]};
        var dr=document.getElementById('rmSiteDr').value;if(dr)body.dr=Number(dr);
        fetch(API+'/v1/hub/sites',{method:'POST',headers:authed(),body:JSON.stringify(body)})
        .then(function(r){return r.json()}).then(function(d){
          if(d.submitted){msg('rmSiteMsg',T('subok')+d.pointsEarned+T('pts'),true);rmLoadDash();}
          else msg('rmSiteMsg',(d.error||'error')+(d.flags?': '+d.flags.join(', '):''),false);
        }).catch(function(){msg('rmSiteMsg',T('neterr'),false)});
      };
      window.rmSetWishlist=function(){
        var topics=document.getElementById('rmWishTopics').value.split(',').map(function(t){return t.trim()}).filter(Boolean);
        fetch(API+'/v1/hub/wishlist',{method:'POST',headers:authed(),
          body:JSON.stringify({siteId:document.getElementById('rmWishSite').value.trim(),topics:topics})})
        .then(function(r){return r.json()}).then(function(d){
          msg('rmWishMsg',d.wishlist?(T('wishok')+d.wishlist.topics.join(', ')):(d.error||'error'),!!d.wishlist);
        }).catch(function(){msg('rmWishMsg',T('neterr'),false)});
      };
      rmSetLang(lang());
      if(key())rmLoadDash();
    })();
    </script>
    <?php return ob_get_clean();
}
add_shortcode('recmoment_hub_portal', 'recmoment_hub_portal_shortcode');
