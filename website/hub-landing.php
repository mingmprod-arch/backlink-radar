
// ── Backlink Hub 頁（/hub/）：Adora 淺色風（同首頁 + /hub-portal/ 一套設計語言）──
// 此檔係 WP Code Snippets snippet 5 嘅版本控制副本。改法：改呢度 → push → 經 wp-admin fetch api.github.com 更新 snippet。
function recmoment_hub_shortcode() {
    ob_start(); ?>
    <div class="rmh">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <style>
      /* ── 成頁淺色（同首頁一致；Assembler header/footer 用返主題原色）── */
      body.page-id-127{background:#fff!important}
      body.page-id-127 .entry-content{padding-top:0!important}
    </style>
    <style>
      .rmh{background:#ffffff;color:#353241;font-family:'Plus Jakarta Sans',-apple-system,'Helvetica Neue',Arial,sans-serif;font-size:15px;line-height:1.65;border:1px solid #eceaf6;border-radius:20px;max-width:1080px;margin:24px auto;overflow:hidden;box-shadow:0 12px 40px rgba(33,22,76,.08)}
      .rmh *{box-sizing:border-box}
      .rmh ::selection{background:#6C63FF;color:#fff}
      .rmh a{color:#6C63FF;text-decoration:none;font-weight:600}
      .rmh a:hover{text-decoration:underline}
      .rmh code{font-family:ui-monospace,Menlo,monospace;font-size:.92em;background:#f4f2ff;border-radius:6px;padding:1px 6px;color:#5a4fcf}
      /* ── top bar ── */
      .rmh-top{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #f0edf9;padding:16px 28px;background:#fbfaff}
      .rmh-brand{font-size:15px;font-weight:800;letter-spacing:-0.01em;color:#21164c}
      .rmh-brand b{color:#6C63FF;font-weight:800}
      .rmh-topr{display:flex;align-items:center;gap:14px}
      .rmh-ver{font-size:12px;color:#9b93c9;letter-spacing:.02em}
      .rmh-portal-link{font-size:13px;border:1.5px solid #d9d3fb;color:#6C63FF;padding:6px 16px;border-radius:999px;font-weight:700}
      .rmh-portal-link:hover{background:#f4f2ff;text-decoration:none}
      /* ── hero ── */
      .rmh-hero{border-bottom:1px solid #f0edf9;padding:44px 32px 36px;background:linear-gradient(135deg,#f8f6ff 0%,#fff 60%)}
      .rmh-hline{font-size:14px;font-family:ui-monospace,Menlo,monospace}
      .rmh-pr{color:#6C63FF;font-weight:700}
      .rmh-cm{color:#b3aede}
      .rmh-cmd{color:#21164c;font-weight:700}
      .rmh-cur{display:inline-block;width:8px;height:15px;background:#6C63FF;vertical-align:-2px;margin-left:4px;animation:rmhblink 1.1s steps(1) infinite;border-radius:2px}
      @keyframes rmhblink{50%{opacity:0}}
      .rmh-hero h1{font-size:clamp(28px,4vw,42px);line-height:1.2;font-weight:800;color:#21164c;margin:22px 0 16px;letter-spacing:-0.02em}
      .rmh-hero h1 .acc{color:#6C63FF}
      .rmh-copy{font-size:15px;line-height:1.8;color:#5f5f69;max-width:680px;margin:0 0 8px}
      .rmh-copy b{color:#21164c}
      .rmh-cta{margin-top:24px}
      .rmh .btn{background:#6C63FF;color:#fff!important;border:0;border-radius:10px;padding:12px 26px;font-size:14px;font-family:inherit;font-weight:700;cursor:pointer;display:inline-block;box-shadow:0 4px 14px rgba(108,99,255,.28);transition:transform .08s ease,box-shadow .15s ease}
      .rmh .btn:hover{background:#5a4fcf;text-decoration:none;transform:translateY(-1px);box-shadow:0 8px 20px rgba(108,99,255,.32)}
      .rmh .btn.ghost{background:#fff;color:#6C63FF!important;border:1.5px solid #d9d3fb;box-shadow:none}
      .rmh .btn.ghost:hover{background:#f4f2ff;border-color:#6C63FF;transform:translateY(-1px)}
      /* ── sections ── */
      .rmh-sec{border-top:1px solid #f0edf9;padding:36px 32px}
      .rmh-sec-h{display:flex;align-items:center;gap:10px;margin-bottom:20px;flex-wrap:wrap}
      .rmh-sec-h .idx{color:#c5bfe8;font-size:13px;font-weight:700}
      .rmh-sec-h .ttl{font-size:13px;letter-spacing:.12em;text-transform:uppercase;color:#21164c;font-weight:800}
      .rmh-sec-h .chip{margin-left:auto;font-size:11px;color:#8a84b0;border:1px solid #e3defc;background:#fbfaff;padding:3px 10px;border-radius:999px;white-space:nowrap;font-weight:600}
      .rmh-h2{font-size:clamp(20px,2.6vw,26px);font-weight:800;color:#21164c;margin:0 0 16px;letter-spacing:-0.01em}
      /* ── live stats ── */
      .rmh .stats{display:flex;flex-wrap:wrap;margin-top:8px}
      .rmh .stat{padding:2px 32px 2px 0;margin:0 32px 12px 0}
      .rmh .stat+.stat{border-left:1px solid #f0edf9;padding-left:32px}
      .rmh .stat-num{display:block;font-size:38px;font-weight:800;color:#21164c;line-height:1.2;letter-spacing:-0.02em}
      .rmh .stat.hot .stat-num{color:#6C63FF}
      .rmh .stat-lbl{font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:#9b93c9;margin-top:4px;font-weight:700}
      /* ── steps ── */
      .rmh .step{display:flex;gap:14px;padding:14px 0;border-bottom:1px solid #f6f4fd;align-items:baseline}
      .rmh .step:last-child{border-bottom:0}
      .rmh .step .n{color:#6C63FF;font-size:13px;flex:none;width:40px;font-weight:800;font-family:ui-monospace,Menlo,monospace}
      .rmh .step .t{color:#21164c;font-weight:700}
      .rmh .step .d{color:#8a84b0;font-size:13px;margin-top:2px;line-height:1.7}
      /* ── pricing ── */
      .rmh .price-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;margin-top:8px}
      .rmh .plan{padding:24px;border:1px solid #eceaf6;border-radius:16px;background:#fff;transition:box-shadow .15s ease,transform .1s ease}
      .rmh .plan:hover{box-shadow:0 10px 30px rgba(33,22,76,.08);transform:translateY(-2px)}
      .rmh .plan.hot{background:linear-gradient(160deg,#f8f6ff,#fff);border:1.5px solid #6C63FF;box-shadow:0 12px 32px rgba(108,99,255,.16)}
      .rmh .plan .tag{display:inline-block;font-size:11px;letter-spacing:.12em;text-transform:uppercase;color:#6C63FF;background:#f4f2ff;border-radius:999px;padding:3px 10px;margin-bottom:12px;font-weight:700}
      .rmh .plan h3{font-size:14px;font-weight:800;color:#21164c;margin:0 0 6px;letter-spacing:.06em}
      .rmh .price{font-size:32px;font-weight:800;color:#21164c;margin:0 0 14px;letter-spacing:-0.02em}
      .rmh .price span{font-size:12px;font-weight:500;color:#9b93c9}
      .rmh .plan ul{color:#5f5f69;line-height:2;padding-left:18px;margin:0 0 20px;font-size:13px}
      .rmh .plan ul li::marker{color:#6C63FF}
      /* ── rules ── */
      .rmh ul.rules{color:#5f5f69;line-height:2.1;padding-left:0;margin:0;font-size:14px;list-style:none}
      .rmh ul.rules li::before{content:'✓ ';color:#6C63FF;font-weight:700}
      .rmh .fineprint{font-size:13px;color:#8a84b0;line-height:1.8;margin-top:16px}
      /* ── footer ── */
      .rmh-foot{border-top:1px solid #f0edf9;padding:16px 28px;font-size:11px;letter-spacing:.08em;color:#9b93c9;text-transform:uppercase;display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px;background:#fbfaff;font-weight:600}
      @media (max-width:900px){
        .rmh .stat+.stat{padding-left:16px;margin-left:0}
        .rmh-hero,.rmh-sec{padding-left:20px;padding-right:20px}
      }
    </style>

    <div class="rmh-top">
      <div class="rmh-brand">Rec Moment<b> · </b>Backlink Hub</div>
      <div class="rmh-topr">
        <span class="rmh-ver">network v2.0</span>
        <a class="rmh-portal-link" href="https://recmoment.net/hub-portal/">會員中心 →</a>
      </div>
    </div>

    <div class="rmh-hero">
      <div class="rmh-hline"><span class="rmh-pr">guest@recmoment</span><span class="rmh-cm">:~$</span> <span class="rmh-cmd">hub --join</span><span class="rmh-cur"></span></div>
      <h1>唔使再逐個站求人。<br>講句「我要 backlink」，<span class="acc">系統自動對接</span>。</h1>
      <p class="rmh-copy"><b>三步，60 秒：</b>① 免費攞你嘅 key → ② 貼落你嘅 AI（Claude / Cursor / Kimi）→ ③ 佢自動幫你喺 130+ 個已審核網站入面搵位、出提案、跟到刊登為止。出版方永遠有最終決定權，付費 placement 自動帶 sponsored 標記。</p>
      <details class="rmh-copy" style="cursor:pointer"><summary style="color:#9b93c9;font-weight:600">技術細節（工程師先睇）▼</summary><span style="color:#8a84b0">MCP server + REST API。關鍵字/主題詞庫/wishlist 三重配對，成交經爬蟲驗證（link + rel 屬性），DR 加權積分結算，月度 niche benchmark。全部數據來自真實成交，唔係 marketing 數。</span></details>
      <p class="rmh-cta"><a class="btn" href="https://recmoment.net/hub-portal/">免費攞 API key →</a><a class="btn ghost" href="#hub-join" style="margin-left:10px">睇收費</a></p>
    </div>

    <div class="rmh-sec" id="hub-live">
      <div class="rmh-sec-h"><span class="idx">// 00</span><span class="ttl">Live network data</span><span class="chip">GET /hub/public/stats</span></div>
      <p class="rmh-copy">呢啲數字由 Hub API 實時讀取——唔係寫出嚟嘅 marketing 數。</p>
      <div class="stats" id="rmHubStats"></div>
    </div>

    <div class="rmh-sec" id="hub-bench">
      <div class="rmh-sec-h"><span class="idx">// 00b</span><span class="ttl">Niche benchmarks</span><span class="chip">GET /hub/public/benchmarks</span></div>
      <p class="rmh-copy">真實成交數據，按 niche 拆——邊個領域嘅出版方最肯回覆、成交最快。呢份表本身就係我哋嘅護城河：對手冇成交數據，永遠落後。</p>
      <div id="rmHubBench" style="overflow-x:auto"></div>
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
          <span class="tag">最受歡迎</span>
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
      <h2 class="rmh-h2">我哋賣資料同配對，<span style="color:#6C63FF">永遠唔賣 link</span>。</h2>
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

      fetch('https://hub.recmoment.net/hub/public/benchmarks').then(function(r){return r.json()}).then(function(d){
        var bb=document.getElementById('rmHubBench');if(!bb||!d.niches||!d.niches.length)return;
        var rows=d.niches.filter(function(n){return n.proposals>0||n.sites>1}).slice(0,8);
        if(!rows.length)rows=d.niches.slice(0,8);
        // DOM 構建（唔用 innerHTML）：niche 係會員提交嘅資料，防 stored XSS
        var tbl=document.createElement('table');tbl.style.cssText='width:100%;border-collapse:collapse;font-size:13px';
        var ths=['NICHE','已審核站','提案','回覆率','成交','成交日數(中位)'];
        var hr=document.createElement('tr');
        ths.forEach(function(t,i){var th=document.createElement('th');th.textContent=t;th.style.cssText=(i?'':'text-align:left;')+'color:#9b93c9;padding:8px;font-size:11px;letter-spacing:.1em;text-transform:uppercase;border-bottom:1px solid #eceaf6';hr.appendChild(th)});
        tbl.appendChild(hr);
        rows.forEach(function(n){
          var tr=document.createElement('tr');
          var cells=[String(n.niche),String(n.sites),String(n.proposals),n.responseRate!=null?Math.round(n.responseRate*100)+'%':'—',String(n.published),n.medianDaysToPublish!=null?Math.round(n.medianDaysToPublish)+'d':'—'];
          cells.forEach(function(c,i){
            var td=document.createElement('td');td.textContent=c;
            td.style.cssText=(i?'text-align:center;':'')+'color:'+(i===0?'#21164c;font-weight:700':(i===3?'#6C63FF;font-weight:700':'#5f5f69'))+';padding:8px;border-bottom:1px solid #f6f4fd';
            tr.appendChild(td);
          });
          tbl.appendChild(tr);
        });
        bb.textContent='';bb.appendChild(tbl);
      }).catch(function(){var s=document.getElementById('hub-bench');if(s)s.style.display='none'});
    })();
    </script>
    <?php return ob_get_clean();
}
add_shortcode('recmoment_hub', 'recmoment_hub_shortcode');
