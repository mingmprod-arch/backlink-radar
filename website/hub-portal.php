
// ── Hub 會員 Portal（/hub-portal/）：GitHub 風 dark dev 介面 + 中英切換 ──
function recmoment_hub_portal_shortcode() {
    ob_start(); ?>
    <div id="rmPortal" class="rmp">
    <style>
      .rmp{background:#0d1117;color:#c9d1d9;font-family:-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;border-radius:12px;padding:28px;max-width:860px;margin:0 auto}
      .rmp *{box-sizing:border-box}
      .rmp code,.rmp .mono{font-family:ui-monospace,SFMono-Regular,'SF Mono',Menlo,monospace}
      .rmp-top{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #21262d;padding-bottom:16px;margin-bottom:20px}
      .rmp-brand{font-size:18px;font-weight:700;color:#f0f6fc}
      .rmp-brand span{color:#3fb950}
      .rmp-lang{display:flex;border:1px solid #30363d;border-radius:8px;overflow:hidden}
      .rmp-lang button{background:transparent;color:#8b949e;border:0;padding:6px 14px;font-size:13px;cursor:pointer}
      .rmp-lang button.on{background:#21262d;color:#f0f6fc}
      .rmp-card{background:#0d1117;border:1px solid #30363d;border-radius:10px;margin-bottom:18px}
      .rmp-card-h{border-bottom:1px solid #21262d;padding:12px 16px;font-weight:600;color:#f0f6fc;font-size:14px;display:flex;align-items:center;gap:8px}
      .rmp-card-h .dot{width:8px;height:8px;border-radius:50%;background:#3fb950}
      .rmp-card-b{padding:16px}
      .rmp label{display:block;font-size:12px;color:#8b949e;margin:10px 0 4px;font-weight:600}
      .rmp input,.rmp select{width:100%;background:#010409;border:1px solid #30363d;color:#c9d1d9;border-radius:6px;padding:8px 12px;font-size:14px;font-family:ui-monospace,SFMono-Regular,Menlo,monospace}
      .rmp input:focus,.rmp select:focus{outline:none;border-color:#58a6ff}
      .rmp .btn{background:#238636;color:#fff;border:1px solid rgba(240,246,252,.1);border-radius:6px;padding:8px 16px;font-size:14px;font-weight:600;cursor:pointer;margin-top:12px}
      .rmp .btn:hover{background:#2ea043}
      .rmp .btn.ghost{background:#21262d}
      .rmp .btn.ghost:hover{background:#30363d}
      .rmp .msg{display:none;margin-top:12px;padding:10px 12px;border-radius:6px;font-size:13px;word-break:break-all}
      .rmp .msg.ok{display:block;background:rgba(63,185,80,.12);border:1px solid #238636;color:#3fb950}
      .rmp .msg.err{display:block;background:rgba(248,81,73,.12);border:1px solid #f85149;color:#f85149}
      .rmp .stat{display:inline-block;background:#161b22;border:1px solid #30363d;border-radius:8px;padding:12px 20px;margin:4px 8px 4px 0;text-align:center}
      .rmp .stat b{display:block;font-size:26px;color:#58a6ff;font-family:ui-monospace,Menlo,monospace}
      .rmp .stat span{font-size:12px;color:#8b949e}
      .rmp .chip{display:inline-block;background:#1f6feb33;border:1px solid #1f6feb;color:#58a6ff;font-size:11px;border-radius:10px;padding:2px 8px;font-family:ui-monospace,Menlo,monospace;margin-left:8px}
      .rmp .hint{font-size:12px;color:#8b949e;line-height:1.6}
      .rmp .hidden{display:none}
      .rmp table.runs{width:100%;border-collapse:collapse;font-size:13px}
      .rmp table.runs th{text-align:left;color:#8b949e;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.04em;padding:8px 10px;border-bottom:1px solid #21262d}
      .rmp table.runs td{padding:10px;border-bottom:1px solid #21262d;color:#c9d1d9;vertical-align:top}
      .rmp .pill{display:inline-block;font-size:11px;border-radius:9999px;padding:2px 10px;font-weight:600}
      .rmp .pill.ok{background:#3fb9501a;color:#3fb950;border:1px solid #3fb950}
      .rmp .pill.wait{background:#d299221a;color:#d29922;border:1px solid #d29922}
      .rmp .pill.no{background:#f851491a;color:#f85149;border:1px solid #f85149}
    </style>

    <div class="rmp-top">
      <div class="rmp-brand">recmoment<span>@</span>backlink-hub</div>
      <div class="rmp-lang">
        <button id="rmLangZh" class="on" onclick="rmSetLang('zh')">中文</button>
        <button id="rmLangEn" onclick="rmSetLang('en')">EN</button>
      </div>
    </div>

    <div class="rmp-card">
      <div class="rmp-card-h"><span class="dot"></span><span data-i18n="t1">取得 API key（免費）</span><span class="chip">POST /hub/public/signup</span></div>
      <div class="rmp-card-b">
        <p class="hint" data-i18n="d1">免費計劃：目錄查詢每月 20 次。之後用積分解鎖配對——交站賺分（+0.5/DR）、成交驗證 +15。</p>
        <label>email</label>
        <input type="email" id="rmEmail" placeholder="you@example.com">
        <button class="btn" onclick="rmSignup()" data-i18n="b1">$ join --free</button>
        <p class="hint" style="margin-top:14px" data-i18n="d1b">已經有 key？直接連接：</p>
        <input type="text" id="rmKey" placeholder="rmh_...">
        <button class="btn ghost" onclick="rmSaveKey()" data-i18n="b1b">connect</button>
        <div class="msg" id="rmJoinMsg"></div>
      </div>
    </div>

    <div class="rmp-card hidden" id="portal-dash">
      <div class="rmp-card-h"><span class="dot"></span><span data-i18n="t2">帳戶狀態</span><span class="chip">GET /v1/hub/points</span></div>
      <div class="rmp-card-b">
        <div class="stat"><b id="rmPoints">–</b><span data-i18n="s1">積分結餘</span></div>
        <div class="stat"><b id="rmMatches">–</b><span>matches = 20 pts</span></div>
        <p class="hint" style="margin-top:12px"><span data-i18n="ref">推薦碼</span>: <code id="rmRef" class="mono">–</code></p>
        <p class="hint" id="rmHist"></p>
      </div>
    </div>

    <div class="rmp-card hidden" id="portal-submit">
      <div class="rmp-card-h"><span class="dot"></span><span data-i18n="t3">提交網站賺分</span><span class="chip">POST /v1/hub/sites</span></div>
      <div class="rmp-card-b">
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

    <div class="rmp-card hidden" id="portal-wish">
      <div class="rmp-card-h"><span class="dot"></span><span data-i18n="t4">Wishlist（出版方）</span><span class="chip">POST /v1/hub/wishlist</span></div>
      <div class="rmp-card-b">
        <p class="hint" data-i18n="d4">話俾買家知你而家想收咩題——撞中 wishlist 嘅配對請求 +3 分排前。</p>
        <label>site_id</label><input type="text" id="rmWishSite" placeholder="site_...">
        <label data-i18n="l4">想收嘅題（逗號分隔）</label><input type="text" id="rmWishTopics" placeholder="ai seo tools, link building">
        <button class="btn" onclick="rmSetWishlist()" data-i18n="b4">$ wishlist --set</button>
        <div class="msg" id="rmWishMsg"></div>
      </div>
    <div class="rmp-card hidden" id="portal-runs">
      <div class="rmp-card-h"><span class="dot"></span><span data-i18n="t5">Runs（配對紀錄）</span><span class="chip">GET /v1/hub/runs</span>
        <span style="margin-left:auto;display:flex;gap:6px">
          <button class="btn ghost" style="padding:4px 12px;font-size:12px" id="rmRunsViewBtn" onclick="rmToggleRunsView()">JSON</button>
          <button class="btn ghost" style="padding:4px 12px;font-size:12px" onclick="rmExportRuns()">Export</button>
        </span>
      </div>
      <div class="rmp-card-b">
        <div id="rmRunsTable"></div>
        <pre id="rmRunsJson" class="hidden mono" style="background:#161b22;border:1px solid #30363d;border-radius:8px;padding:14px;font-size:12px;overflow:auto;max-height:400px;color:#c9d1d9"></pre>
      </div>
    <div class="rmp-card hidden" id="portal-upgrade">
      <div class="rmp-card-h"><span class="dot"></span><span data-i18n="t6">升級解鎖</span><span class="chip">stripe</span></div>
      <div class="rmp-card-b">
        <p class="hint" data-i18n="d6">Member $19 一次性：100 次/月查詢。Pro $49/月：無限查詢 + 自動配對。Agency $199/月：25 站 + 白標報告。付款後 plan 自動升級。</p>
        <a class="btn ghost" style="text-decoration:none;margin-right:8px" href="https://buy.stripe.com/dRm00c6BzcF563k2FC1Jm01" target="_blank">Member $19</a>
        <a class="btn" style="text-decoration:none;margin-right:8px" href="https://buy.stripe.com/fZuaEQe41eNd9fwa841Jm02" target="_blank">Pro $49/月</a>
        <a class="btn ghost" style="text-decoration:none" href="https://buy.stripe.com/5kQ5kw6BzawX77ocgc1Jm03" target="_blank">Agency $199/月</a>
      </div>
    </div>
    </div>

    <script>
    (function(){
      var API='https://hub.recmoment.net';
      var I18N={
        zh:{t1:'取得 API key（免費）',d1:'免費計劃：目錄查詢每月 20 次。之後用積分解鎖配對——交站賺分（+0.5/DR）、成交驗證 +15。',b1:'$ join --free',d1b:'已經有 key？直接連接：',b1b:'connect',t2:'帳戶狀態',s1:'積分結餘',ref:'推薦碼',t3:'提交網站賺分',d3:'交一個你擁有、肯收投稿嘅站。DR 越高分越多（封頂 50）。逐站審核，PBN 拒收，同一 domain 全網只計一次分。',b3:'$ submit --earn',t4:'Wishlist（出版方）',d4:'話俾買家知你而家想收咩題——撞中 wishlist 嘅配對請求 +3 分排前。',l4:'想收嘅題（逗號分隔）',b4:'$ wishlist --set',
          neterr:'網絡錯誤，稍後再試',keybad:'key 格式唔啱（rmh_ 開頭 48 位 hex）',linked:'✓ 已連接',keyonce:'✓ 你嘅 key（只顯示一次，請即抄低）：',subok:'✓ 已提交，賺咗 ',pts:' 分；審核後上架。',wishok:'✓ 已更新：',recent:'最近：',t5:'Runs（配對紀錄）',noruns:'仲未有配對紀錄。用 match 工具發起第一次配對啦。',t6:'升級解鎖',d6:'Member $19 一次性：100 次/月查詢。Pro $49/月：無限查詢 + 自動配對。Agency $199/月：25 站 + 白標報告。付款後 plan 自動升級。',copied:'✓ 已複製',paid:'✓ 付款成功！多謝支持。升級會喺 24 小時內生效，請用付款 email 喺下面 connect 你嘅 key。'},
        en:{t1:'Get your API key (free)',d1:'Free plan: 20 directory queries/month. Unlock matches with points — submit sites (+0.5/DR), verified outcomes +15.',b1:'$ join --free',d1b:'Already have a key? Connect it:',b1b:'connect',t2:'Account',s1:'points balance',ref:'Referral link',t3:'Submit a site, earn points',d3:'Submit a site you own that accepts contributions. Higher DR earns more (cap 50). Every site is vetted — PBNs rejected, one point grant per domain network-wide.',b3:'$ submit --earn',t4:'Wishlist (publishers)',d4:'Tell buyers what topics you want right now — matches hitting your wishlist score +3.',l4:'Wanted topics (comma-separated)',b4:'$ wishlist --set',
          neterr:'Network error, try again later',keybad:'Invalid key format (rmh_ + 48 hex)',linked:'✓ Connected',keyonce:'✓ Your key (shown once — save it now): ',subok:'✓ Submitted, earned ',pts:' pts; listed after review.',wishok:'✓ Updated: ',recent:'latest: ',t5:'Runs (match history)',noruns:'No runs yet — fire your first match request to get going.',t6:'Upgrade',d6:'Member $19 one-time: 100 queries/mo. Pro $49/mo: unlimited queries + auto-match. Agency $199/mo: 25 sites + white-label reports. Plan upgrades automatically after payment.',copied:'✓ Copied',paid:'✓ Payment received — thank you! Your upgrade activates within 24 hours. Connect your key below with the email you paid with.'}
      };
      window.rmSetLang=function(l){
        var d=I18N[l]||I18N.zh;
        document.querySelectorAll('#rmPortal [data-i18n]').forEach(function(e){var k=e.getAttribute('data-i18n');if(d[k])e.textContent=d[k]});
        document.getElementById('rmLangZh').className=l==='zh'?'on':'';
        document.getElementById('rmLangEn').className=l==='en'?'on':'';
        localStorage.setItem('rmHubLang',l);
      };
      function lang(){return localStorage.getItem('rmHubLang')||'zh'}
      function T(k){return (I18N[lang()]||I18N.zh)[k]||k}
      function key(){return localStorage.getItem('rmHubKey')||''}
      function msg(id,text,ok){var e=document.getElementById(id);e.textContent=text;e.className='msg '+(ok?'ok':'err')}
      function authed(){return {'content-type':'application/json','authorization':'Bearer '+key()}}
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
      window.rmLoadDash=function(){
        fetch(API+'/v1/hub/points',{headers:authed()}).then(function(r){if(!r.ok)throw 0;return r.json()})
        .then(function(d){
          document.getElementById('rmPoints').textContent=d.balance;
          document.getElementById('rmMatches').textContent=Math.floor(d.balance/20);
          document.getElementById('rmHist').textContent=d.history.length?(T('recent')+d.history[0].reason):'';
          fetch(API+'/v1/hub/referral-code',{headers:authed()}).then(function(r){return r.json()})
          .then(function(r2){document.getElementById('rmRef').textContent=r2.shareUrl||r2.code});
          ['portal-dash','portal-submit','portal-wish','portal-runs','portal-upgrade'].forEach(function(id){document.getElementById(id).classList.remove('hidden')});
          rmLoadRuns();
        }).catch(function(){});
      };
      // ── Runs（Apify 式）：DOM 構建（避免 wpautop 吃掉 block 標籤字串）──
      var rmRunsData=null,rmRunsJsonMode=false;
      function rmEl(tag,cls,text){var e=document.createElement(tag);if(cls)e.className=cls;if(text!=null)e.textContent=text;return e}
      function rmPillEl(status){
        var cls=/published|approved|verified/.test(status)?'pill ok':(/reject|fail|no_reply/.test(status)?'pill no':'pill wait');
        return rmEl('span',cls,status);
      }
      function rmRenderRuns(){
        var box=document.getElementById('rmRunsTable'),pre=document.getElementById('rmRunsJson');
        box.textContent='';
        if(rmRunsJsonMode){
          pre.classList.remove('hidden');
          pre.textContent=JSON.stringify(rmRunsData,null,2);
          document.getElementById('rmRunsViewBtn').textContent='Table';return;
        }
        pre.classList.add('hidden');document.getElementById('rmRunsViewBtn').textContent='JSON';
        var runs=(rmRunsData&&rmRunsData.runs)||[];
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
            if(o.liveUrl){var a=rmEl('a',null,t);a.href=o.liveUrl;a.target='_blank';a.style.color='#58a6ff';tdO.appendChild(a)}
            else tdO.appendChild(document.createTextNode(t));
          })}else{var s=rmEl('span',null,'—');s.style.color='#8b949e';tdO.appendChild(s)}
          row.appendChild(tdO);
          var tdD=rmEl('td','mono',(r.createdAt||'').slice(0,10));tdD.style.color='#8b949e';row.appendChild(tdD);
          tb.appendChild(row);
        });
        box.appendChild(tb);
      }
      window.rmLoadRuns=function(){
        fetch(API+'/v1/hub/runs',{headers:authed()}).then(function(r){return r.json()})
        .then(function(d){rmRunsData=d;rmRenderRuns()}).catch(function(){});
      };
      window.rmToggleRunsView=function(){rmRunsJsonMode=!rmRunsJsonMode;rmRenderRuns()};
      window.rmExportRuns=function(){
        var blob=new Blob([JSON.stringify(rmRunsData,null,2)],{type:'application/json'});
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
