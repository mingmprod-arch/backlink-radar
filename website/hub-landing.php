
// ── Backlink Hub 頁（/hub/）：Helloivy 風 — cream 紙感 + 黑墨 + Unbounded ──
function recmoment_hub_shortcode() {
    ob_start(); ?>
    <div class="rmh">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@400;500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
      .rmh{background:#faf6f0;color:#000;font-family:'Inter',ui-sans-serif,system-ui,sans-serif;letter-spacing:-0.02em;border-radius:12px;padding:64px 40px;max-width:1200px;margin:0 auto}
      .rmh *{box-sizing:border-box}
      .rmh h1,.rmh h2{font-family:'Unbounded','Inter',sans-serif;font-weight:500;color:#000;margin:0 0 20px}
      .rmh h1{font-size:clamp(34px,5.2vw,62px);line-height:1.15;letter-spacing:.01em}
      .rmh h2{font-size:clamp(24px,3.4vw,34px);line-height:1.2}
      .rmh .eyebrow{font-size:12px;font-weight:500;text-transform:uppercase;letter-spacing:.05em;color:#5e697f;margin:0 0 14px}
      .rmh .copy{font-size:16px;line-height:1.6;color:#5e697f;max-width:640px;margin:0 0 8px}
      .rmh section{margin-bottom:80px}
      .rmh .btn{display:inline-block;background:#000;color:#fff;font-size:14px;font-weight:500;border-radius:9999px;padding:10px 24px;text-decoration:none;border:0;cursor:pointer}
      .rmh .btn:hover{opacity:.85}
      .rmh .btn.ghost{background:transparent;color:#000;border:1px solid #000}
      .rmh .card{background:#fff;border-radius:8px;padding:24px}
      .rmh .price-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-top:24px}
      .rmh .price-grid .card h3{font-family:'Unbounded',sans-serif;font-weight:500;font-size:18px;margin:0 0 6px}
      .rmh .price{font-size:28px;font-weight:700;margin:0 0 14px}
      .rmh .price span{font-size:14px;font-weight:400;color:#5e697f}
      .rmh .price-grid ul{color:#5e697f;line-height:1.9;padding-left:18px;margin:0 0 20px;font-size:14px}
      .rmh .price-grid .card.hot{border:1px solid #000}
      .rmh .price-grid .card:not(.hot){border:1px solid #e3ddd2}
      .rmh ol.steps{line-height:2;color:#5e697f;padding-left:22px}
      .rmh ol.steps strong{color:#000}
      .rmh ul.rules{color:#5e697f;line-height:2;padding-left:20px}
      .rmh .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:16px;margin-top:20px}
      .rmh .stat-num{font-family:'Unbounded',sans-serif;font-size:34px;font-weight:500}
      .rmh .stat-lbl{font-size:12px;color:#5e697f;text-transform:uppercase;letter-spacing:.05em;margin-top:6px}
      .rmh .hairline{border-top:1px solid #e3ddd2}
      .rmh code{background:#fff;border:1px solid #e3ddd2;border-radius:6px;padding:2px 7px;font-size:13px}
      .rmh .fineprint{font-size:14px;color:#5e697f;line-height:1.6;margin-top:16px}
    </style>

    <section>
      <p class="eyebrow">Backlink Hub · by Rec Moment</p>
      <h1>Backlink 配對網絡，<br>由系統自動對接。</h1>
      <p class="copy">提交你嘅目標網址，系統自動喺已審核嘅網站目錄入面搵到主題相關嘅出版方、草擬插入建議、對方一撳批准、落地後爬蟲驗證、自動結算同出月報。你嘅 AI agent 仲可以經 MCP 或 REST API 直接用。</p>
      <p style="margin-top:24px"><a class="btn" href="#hub-join">加入 Hub</a> <a class="btn ghost" href="https://recmoment.net/hub-portal/" style="margin-left:10px">免費攞 API key</a></p>
    </section>

    <section class="hairline" style="padding-top:64px">
      <p class="eyebrow">How it works</p>
      <h2>五步，全自動。</h2>
      <ol class="steps">
        <li><strong>提交目標</strong>：話俾系統知你想邊個頁面被 link，加主題關鍵字</li>
        <li><strong>自動配對</strong>：喺已審核目錄搵主題相關、DR 達標嘅出版方</li>
        <li><strong>自動撰稿</strong>：生成建議段落、anchor 同插入位置</li>
        <li><strong>一撳批准</strong>：出版方批准先落地（付費合作自動加 rel="sponsored"）</li>
        <li><strong>自動驗證 + 結算</strong>：爬蟲覆核 link 在唔在、rel 屬性，成功先扣額，失敗全退</li>
      </ol>
    </section>

    <section class="hairline" style="padding-top:64px" id="hub-join">
      <p class="eyebrow">Membership</p>
      <h2>三個等級，按需要升級。</h2>
      <div class="price-grid">
        <div class="card">
          <h3>Member</h3>
          <p class="price">US$19<span> 一次性</span></p>
          <ul>
            <li>查閱已審核網站目錄（每月 100 次）</li>
            <li>登記 1 個自己嘅站</li>
            <li>Link-gap 月報</li>
          </ul>
          <a class="btn ghost" href="https://buy.stripe.com/dRm00c6BzcF563k2FC1Jm01">加入 Member</a>
        </div>
        <div class="card hot">
          <h3>Pro</h3>
          <p class="price">US$49<span> /月</span></p>
          <ul>
            <li>無限查詢目錄</li>
            <li>登記 5 個站</li>
            <li>Auto-Match 自動配對 + 即時警示</li>
            <li>MCP + REST API 存取</li>
          </ul>
          <a class="btn" href="https://buy.stripe.com/fZuaEQe41eNd9fwa841Jm02">訂閱 Pro</a>
        </div>
        <div class="card">
          <h3>Agency</h3>
          <p class="price">US$199<span> /月</span></p>
          <ul>
            <li>Pro 全部功能</li>
            <li>登記 25 個站、多席位</li>
            <li>白標報告 export</li>
          </ul>
          <a class="btn ghost" href="https://buy.stripe.com/5kQ5kw6BzawX77ocgc1Jm03">訂閱 Agency</a>
        </div>
      </div>
      <p class="fineprint">免費玩家都可以 join：每月 20 次目錄查詢，交站賺積分（+0.5/DR）、成交驗證 +15，積分解鎖配對。去 <a href="https://recmoment.net/hub-portal/" style="color:#000">會員中心</a> 攞免費 API key。</p>
    </section>

    <section class="hairline" style="padding-top:64px">
      <p class="eyebrow">Compliance</p>
      <h2>我哋賣資料同配對，永遠唔賣 link。</h2>
      <ul class="rules">
        <li>不保證任何 link 會出現；dofollow 定 nofollow 係站主決定</li>
        <li>涉及金錢嘅合作，生成嘅 link 一律帶 rel="sponsored"</li>
        <li>目錄逐站審核：PBN、link farm 特徵直接拒收</li>
        <li>違反守則嘅會員會被移除，積分唔退</li>
      </ul>
      <p class="fineprint">免費嘅 backlink-radar skill 照舊免費開源；Hub 係俾想慳返 outreach 時間嘅人。</p>
    </section>

    <section class="hairline" style="padding-top:64px" id="hub-live">
      <p class="eyebrow">Live network data</p>
      <h2>網絡實時數據。</h2>
      <p class="copy">呢啲數字由 Hub API 實時讀取——唔係寫出嚟嘅 marketing 數。</p>
      <div class="stats" id="rmHubStats"></div>
      <p class="fineprint">🎁 介紹朋友加入：對方用你嘅推薦碼登記，雙方即時各 +50 次查詢額度。登記後用 <code>GET /v1/hub/referral-code</code> 攞你嘅專屬碼。</p>
    </section>
    <script>
    (function(){
      var sec=document.getElementById('hub-live'),box=document.getElementById('rmHubStats');
      if(!box)return;
      fetch('https://hub.recmoment.net/hub/public/stats').then(function(r){if(!r.ok)throw new Error();return r.json();}).then(function(d){
        // 冷啟動期：0 嘅指標唔晒出嚟（陌生人見到 0 會扣分），只講有嘅
        var items=[['收錄網站',d.sitesListed],['覆蓋 niche',d.nichesCovered]];
        if(d.matchesCreated)items.push(['累計配對',d.matchesCreated]);
        if(d.linksPublished)items.push(['成功刊登',d.linksPublished]);
        items.forEach(function(it){
          var card=document.createElement('div');card.className='card';card.style.textAlign='center';
          var num=document.createElement('div');num.className='stat-num';num.textContent=it[1];
          var lbl=document.createElement('div');lbl.className='stat-lbl';lbl.textContent=it[0];
          card.appendChild(num);card.appendChild(lbl);box.appendChild(card);
        });
      }).catch(function(){if(sec)sec.style.display='none';});
    })();
    </script>
    </div>
    <?php return ob_get_clean();
}
add_shortcode('recmoment_hub', 'recmoment_hub_shortcode');
