
// ── Backlink Hub 頁（/hub/）：terminal console 風（同 /hub-portal/ 一套設計語言）──
function recmoment_hub_shortcode() {
    ob_start(); ?>
    <div class="rmh">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap">
    <style>
      /* ── 成頁轉深色（Assembler theme header/footer 透明底，靠 body 透色）── */
      body.page-id-127{background:#0a0a0a!important}
      body.page-id-127 header.wp-block-template-part,
      body.page-id-127 footer.wp-block-template-part{background:#0a0a0a!important}
      body.page-id-127 header.wp-block-template-part{border-bottom:1px solid #1c1c1c}
      body.page-id-127 footer.wp-block-template-part{border-top:1px solid #1c1c1c}
      body.page-id-127 header.wp-block-template-part a,
      body.page-id-127 header.wp-block-template-part p,
      body.page-id-127 header.wp-block-template-part h1,
      body.page-id-127 header.wp-block-template-part h2,
      body.page-id-127 header.wp-block-template-part span{color:#c8c8c8!important}
      body.page-id-127 header.wp-block-template-part a:hover{color:#00d9a3!important}
      body.page-id-127 footer.wp-block-template-part,
      body.page-id-127 footer.wp-block-template-part a,
      body.page-id-127 footer.wp-block-template-part p,
      body.page-id-127 footer.wp-block-template-part h2,
      body.page-id-127 footer.wp-block-template-part h3,
      body.page-id-127 footer.wp-block-template-part li,
      body.page-id-127 footer.wp-block-template-part span{color:#666!important}
      body.page-id-127 .entry-content{padding-top:0!important}
    </style>
    <style>
      .rmh{background:#0a0a0a;color:#d4d4d4;font-family:'JetBrains Mono',ui-monospace,SFMono-Regular,'SF Mono',Menlo,monospace;font-size:13px;line-height:1.65;border:1px solid #222;max-width:1080px;margin:24px auto}
      .rmh *{box-sizing:border-box}
      .rmh ::selection{background:#00d9a3;color:#000}
      .rmh a{color:#00d9a3;text-decoration:none}
      .rmh a:hover{text-decoration:underline}
      .rmh code{background:#000;border:1px solid #262626;padding:2px 7px;font-size:12px;font-family:inherit;color:#c8c8c8}
      /* ── top bar ── */
      .rmh-top{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #222;padding:14px 24px}
      .rmh-brand{font-size:13px;font-weight:700;letter-spacing:.06em;color:#fff}
      .rmh-brand b{color:#00d9a3;font-weight:700}
      .rmh-topr{display:flex;align-items:center;gap:16px}
      .rmh-ver{font-size:11px;color:#555;letter-spacing:.08em}
      .rmh-portal-link{font-size:11px;border:1px solid #2a2a2a;color:#999;padding:4px 12px;letter-spacing:.05em}
      .rmh-portal-link:hover{color:#00d9a3;border-color:#00d9a3;text-decoration:none}
      /* ── hero ── */
      .rmh-hero{border-bottom:1px solid #222;padding:40px 24px 32px}
      .rmh-hline{font-size:14px}
      .rmh-pr{color:#00d9a3;font-weight:500}
      .rmh-cm{color:#666}
      .rmh-cmd{color:#fff;font-weight:500}
      .rmh-cur{display:inline-block;width:8px;height:15px;background:#00d9a3;vertical-align:-2px;margin-left:4px;animation:rmhblink 1.1s steps(1) infinite}
      @keyframes rmhblink{50%{opacity:0}}
      .rmh-hero h1{font-size:clamp(26px,4.2vw,44px);line-height:1.25;font-weight:700;color:#fff;margin:22px 0 16px;letter-spacing:0}
      .rmh-hero h1 .acc{color:#00d9a3}
      .rmh-copy{font-size:13px;line-height:1.8;color:#777;max-width:680px;margin:0 0 8px}
      .rmh-cta{margin-top:24px}
      .rmh .btn{background:transparent;color:#00d9a3;border:1px solid #00d9a3;border-radius:0;padding:10px 22px;font-size:13px;font-family:inherit;font-weight:500;letter-spacing:.04em;cursor:pointer;display:inline-block}
      .rmh .btn:hover{background:#00d9a3;color:#000;text-decoration:none}
      .rmh .btn.ghost{color:#999;border-color:#333}
      .rmh .btn.ghost:hover{background:#1a1a1a;color:#fff;border-color:#555;text-decoration:none}
      /* ── sections：border-partitioned ── */
      .rmh-sec{border-top:1px solid #1c1c1c;padding:32px 24px}
      .rmh-sec-h{display:flex;align-items:baseline;gap:10px;margin-bottom:20px}
      .rmh-sec-h .idx{color:#444;font-size:11px}
      .rmh-sec-h .ttl{font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:#999;font-weight:500}
      .rmh-sec-h .chip{margin-left:auto;font-size:10px;color:#555;border:1px solid #2a2a2a;padding:2px 8px;letter-spacing:.05em;white-space:nowrap}
      .rmh-h2{font-size:clamp(18px,2.6vw,24px);font-weight:700;color:#fff;margin:0 0 16px}
      /* ── live stats ── */
      .rmh .stats{display:flex;flex-wrap:wrap;margin-top:8px}
      .rmh .stat{padding:2px 32px 2px 0;margin:0 32px 12px 0}
      .rmh .stat+.stat{border-left:1px solid #222;padding-left:32px}
      .rmh .stat-num{display:block;font-size:38px;font-weight:500;color:#fff;line-height:1.2}
      .rmh .stat.hot .stat-num{color:#00d9a3}
      .rmh .stat-lbl{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#555;margin-top:4px}
      /* ── steps ── */
      .rmh .step{display:flex;gap:14px;padding:12px 0;border-bottom:1px solid #161616;align-items:baseline}
      .rmh .step:last-child{border-bottom:0}
      .rmh .step .n{color:#00d9a3;font-size:12px;flex:none;width:40px}
      .rmh .step .t{color:#e8e8e8;font-weight:500}
      .rmh .step .d{color:#777;font-size:12px;margin-top:2px;line-height:1.7}
      /* ── pricing ── */
      .rmh .price-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:0;border:1px solid #222;margin-top:8px}
      .rmh .plan{padding:22px;border-right:1px solid #1c1c1c}
      .rmh .plan:last-child{border-right:0}
      .rmh .plan.hot{background:rgba(0,217,163,.04);box-shadow:inset 0 0 0 1px #00d9a3}
      .rmh .plan .tag{display:inline-block;font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:#00d9a3;border:1px solid #00d9a355;padding:2px 8px;margin-bottom:12px}
      .rmh .plan h3{font-size:13px;font-weight:700;color:#fff;margin:0 0 6px;letter-spacing:.06em}
      .rmh .price{font-size:30px;font-weight:500;color:#fff;margin:0 0 14px}
      .rmh .price span{font-size:11px;font-weight:400;color:#555}
      .rmh .plan ul{color:#777;line-height:2;padding-left:16px;margin:0 0 20px;font-size:12px}
      .rmh .plan ul li::marker{color:#00d9a3}
      /* ── rules ── */
      .rmh ul.rules{color:#777;line-height:2.1;padding-left:0;margin:0;font-size:12px;list-style:none}
      .rmh ul.rules li::before{content:'✓ ';color:#00d9a3}
      .rmh .fineprint{font-size:12px;color:#666;line-height:1.8;margin-top:16px}
      /* ── footer ── */
      .rmh-foot{border-top:1px solid #222;padding:14px 24px;font-size:10px;letter-spacing:.1em;color:#444;text-transform:uppercase;display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px}
      @media (max-width:900px){
        .rmh .plan{border-right:0;border-bottom:1px solid #1c1c1c}
        .rmh .plan:last-child{border-bottom:0}
        .rmh .stat+.stat{padding-left:16px;margin-left:0}
      }
    </style>

    <div class="rmh-top">
      <div class="rmh-brand">RECMOMENT<b>://</b>BACKLINK-HUB</div>
      <div class="rmh-topr">
        <span class="rmh-ver">network v2.0</span>
        <a class="rmh-portal-link" href="https://recmoment.net/hub-portal/">會員中心 →</a>
      </div>
    </div>

    <div class="rmh-hero">
      <div class="rmh-hline"><span class="rmh-pr">guest@recmoment</span><span class="rmh-cm">:~$</span> <span class="rmh-cmd">hub --join</span><span class="rmh-cur"></span></div>
      <h1>唔使再逐個站求人。<br>講句「我要 backlink」，<span class="acc">系統自動對接</span>。</h1>
      <p class="rmh-copy"><b style="color:#c8c8c8">三步，60 秒：</b>① 免費攞你嘅 key → ② 貼落你嘅 AI（Claude / Cursor / Kimi）→ ③ 佢自動幫你喺 130+ 個已審核網站入面搵位、出提案、跟到刊登為止。出版方永遠有最終決定權，付費 placement 自動帶 sponsored 標記。</p>
      <details class="rmh-copy" style="cursor:pointer"><summary style="color:#555">技術細節（工程師先睇）▼</summary><span style="color:#666">MCP server + REST API。關鍵字/主題詞庫/wishlist 三重配對，成交經爬蟲驗證（link + rel 屬性），DR 加權積分結算，月度 niche benchmark。全部數據來自真實成交，唔係 marketing 數。</span></details>
      <p class="rmh-cta"><a class="btn" href="https://recmoment.net/hub-portal/">$ join --free（免費開始）</a><a class="btn ghost" href="#hub-join" style="margin-left:10px">$ view --pricing</a></p>
    </div>

    <div class="rmh-sec" id="hub-live">
      <div class="rmh-sec-h"><span class="idx">// 00</span><span class="ttl">Live network data</span><span class="chip">GET /hub/public/stats</span></div>
      <p class="rmh-copy">呢啲數字由 Hub API 實時讀取——唔係寫出嚟嘅 marketing 數。</p>
      <div class="stats" id="rmHubStats"></div>
    </div>

    <div class="rmh-sec">
      <div class="rmh-sec-h"><span class="idx">// 01</span><span class="ttl">How it works</span><span class="chip">5 steps, automated</span></div>
      <div class="step"><span class="n">[01]</span><span><span class="t">提交目標</span><div class="d">話俾系統知你想邊個頁面被 link，加主題關鍵字</div></span></div>
      <div class="step"><span class="n">[02]</span><span><span class="t">自動配對</span><div class="d">喺已審核目錄搵主題相關、DR 達標嘅出版方</div></span></div>
      <div class="step"><span class="n">[03]</span><span><span class="t">自動撰稿</span><div class="d">生成建議段落、anchor 同插入位置</div></span></div>
      <div class="step"><span class="n">[04]</span><span><span class="t">一撳批准</span><div class="d">出版方批准先落地（付費合作自動加 rel="sponsored"）</div></span></div>
      <div class="step"><span class="n">[05]</span><span><span class="t">自動驗證 + 結算</span><div class="d">爬蟲覆核 link 在唔在、rel 屬性，成功先扣額，失敗全退</div></span></div>
    </div>

    <div class="rmh-sec" id="hub-join">
      <div class="rmh-sec-h"><span class="idx">// 02</span><span class="ttl">Membership</span><span class="chip">stripe</span></div>
      <div class="price-grid">
        <div class="plan">
          <h3>MEMBER</h3>
          <p class="price">US$19<span> 一次性</span></p>
          <ul>
            <li>查閱已審核網站目錄（每月 100 次）</li>
            <li>登記 1 個自己嘅站</li>
            <li>Link-gap 月報</li>
          </ul>
          <a class="btn ghost" href="https://buy.stripe.com/dRm00c6BzcF563k2FC1Jm01">加入 Member</a>
        </div>
        <div class="plan hot">
          <span class="tag">// recommended</span>
          <h3>PRO</h3>
          <p class="price">US$49<span> /月</span></p>
          <ul>
            <li>無限查詢目錄</li>
            <li>登記 5 個站</li>
            <li>Auto-Match 自動配對 + 即時警示</li>
            <li>MCP + REST API 存取</li>
          </ul>
          <a class="btn" href="https://buy.stripe.com/fZuaEQe41eNd9fwa841Jm02">訂閱 Pro</a>
        </div>
        <div class="plan">
          <h3>AGENCY</h3>
          <p class="price">US$199<span> /月</span></p>
          <ul>
            <li>Pro 全部功能</li>
            <li>登記 25 個站、多席位</li>
            <li>白標報告 export</li>
          </ul>
          <a class="btn ghost" href="https://buy.stripe.com/5kQ5kw6BzawX77ocgc1Jm03">訂閱 Agency</a>
        </div>
      </div>
      <p class="fineprint">免費玩家都可以 join：每月 20 次目錄查詢，交站審批通過後賺積分（+0.5/DR，DR≥10）、成交驗證 +10~30（按站 DR），積分解鎖配對。去 <a href="https://recmoment.net/hub-portal/">會員中心</a> 攞免費 API key。</p>
    </div>

    <div class="rmh-sec">
      <div class="rmh-sec-h"><span class="idx">// 03</span><span class="ttl">Compliance</span><span class="chip">never sell links</span></div>
      <h2 class="rmh-h2">我哋賣資料同配對，<span style="color:#00d9a3">永遠唔賣 link</span>。</h2>
      <ul class="rules">
        <li>不保證任何 link 會出現；dofollow 定 nofollow 係站主決定</li>
        <li>涉及金錢嘅合作，生成嘅 link 一律帶 rel="sponsored"</li>
        <li>目錄逐站審核：PBN、link farm 特徵直接拒收</li>
        <li>違反守則嘅會員會被移除，積分唔退</li>
      </ul>
      <p class="fineprint">免費嘅 backlink-radar skill 照舊免費開源；Hub 係俾想慳返 outreach 時間嘅人。🎁 介紹朋友加入：對方用你嘅推薦碼登記，雙方即時各 +50 次查詢額度。登記後用 <code>GET /v1/hub/referral-code</code> 攞你嘅專屬碼。</p>
    </div>

    <div class="rmh-foot">
      <span>22.3193°N 114.1694°E · HONG KONG · hub.recmoment.net</span>
      <span>唔賣 link · 出版方人工審批 · 付費 placement 一定 sponsored</span>
    </div>
    </div>
    <script>
    (function(){
      var sec=document.getElementById('hub-live'),box=document.getElementById('rmHubStats');
      if(!box)return;
      fetch('https://hub.recmoment.net/hub/public/stats').then(function(r){if(!r.ok)throw new Error();return r.json();}).then(function(d){
        // 冷啟動期：0 嘅指標唔晒出嚟（陌生人見到 0 會扣分），只講有嘅
        var items=[['收錄網站',d.sitesListed,1],['覆蓋 niche',d.nichesCovered,0]];
        if(d.matchesCreated)items.push(['累計配對',d.matchesCreated,0]);
        if(d.linksPublished)items.push(['成功刊登',d.linksPublished,0]);
        items.forEach(function(it){
          var card=document.createElement('div');card.className='stat'+(it[2]?' hot':'');
          var num=document.createElement('span');num.className='stat-num';num.textContent=it[1];
          var lbl=document.createElement('span');lbl.className='stat-lbl';lbl.textContent=it[0];
          card.appendChild(num);card.appendChild(lbl);box.appendChild(card);
        });
      }).catch(function(){if(sec)sec.style.display='none';});
    })();
    </script>
    <?php return ob_get_clean();
}
add_shortcode('recmoment_hub', 'recmoment_hub_shortcode');
