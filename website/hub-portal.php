
// ── Hub 會員 Portal（/hub-portal/）：signup、睇分、交站、wishlist ──
function recmoment_hub_portal_shortcode() {
    ob_start(); ?>
    <?php echo recmoment_page_open('Hub 會員中心', '免費加入 — 分享賺分、選用扣分，一切以數據為本'); ?>
    <style>
      .rm-portal input,.rm-portal select{width:100%;padding:10px 12px;border:1px solid #d0d0d8;border-radius:8px;font-size:15px;margin:4px 0 12px}
      .rm-portal label{font-weight:600;font-size:14px}
      .rm-portal button{background:#111;color:#fff;border:0;border-radius:8px;padding:11px 22px;font-size:15px;cursor:pointer}
      .rm-portal button:hover{background:#333}
      .rm-portal .rm-msg{font-size:14px;margin:8px 0;padding:10px 12px;border-radius:8px;display:none}
      .rm-portal .rm-msg.ok{display:block;background:#e8f7ee;color:#146c2e}
      .rm-portal .rm-msg.err{display:block;background:#fdecea;color:#a12222}
      .rm-portal code{background:#f1f1f4;padding:2px 6px;border-radius:6px;font-size:13px;word-break:break-all}
      .rm-portal .rm-kv{display:flex;gap:24px;flex-wrap:wrap;margin:8px 0}
      .rm-portal .rm-kv div{font-size:15px}
      .rm-portal .rm-kv b{font-size:24px;display:block}
    </style>
    <div class="rm-portal">
      <section class="rm-section" id="portal-join">
        <h2 class="rm-h2">1. 攞你嘅 API key（免費）</h2>
        <p class="rm-copy">留個 email，即刻派一條 free-plan key：目錄查詢每月 20 次，之後靠賺分玩配對。</p>
        <label>Email</label>
        <input type="email" id="rmEmail" placeholder="you@example.com">
        <button onclick="rmSignup()">免費加入</button>
        <div class="rm-msg" id="rmJoinMsg"></div>
        <p class="rm-copy" style="margin-top:14px">已經有 key？直接貼喺度：</p>
        <input type="text" id="rmKey" placeholder="rmh_...">
        <button onclick="rmSaveKey()">連接</button>
      </section>

      <section class="rm-section" id="portal-dash" style="display:none">
        <h2 class="rm-h2">你嘅帳戶</h2>
        <div class="rm-kv">
          <div><b id="rmPoints">–</b>積分結餘</div>
          <div><b id="rmPlan">–</b>計劃</div>
        </div>
        <p class="rm-copy">推薦碼：<code id="rmRef">–</code> — 朋友用呢個碼加入，雙方各得獎勵。</p>
        <p class="rm-copy" id="rmHist"></p>
      </section>

      <section class="rm-section" id="portal-submit" style="display:none">
        <h2 class="rm-h2">2. 分享你嘅站，賺積分</h2>
        <p class="rm-copy">交一個你擁有、肯收投稿嘅站：DR 越高賺越多（+0.5 分 / DR，封頂 50）。目錄逐站審核，PBN 拒收。</p>
        <label>網站 URL</label><input type="url" id="rmSiteUrl" placeholder="https://yourblog.com">
        <label>Niche</label><input type="text" id="rmSiteNiche" placeholder="seo / marketing / tech...">
        <label>接受方式</label>
        <select id="rmSiteAccepts"><option value="guest_post">guest_post</option><option value="resource_page">resource_page</option><option value="niche_edit">niche_edit</option></select>
        <label>DR（如知）</label><input type="number" id="rmSiteDr" placeholder="40" min="0" max="100">
        <button onclick="rmSubmitSite()">提交審核</button>
        <div class="rm-msg" id="rmSiteMsg"></div>
      </section>

      <section class="rm-section" id="portal-wish" style="display:none">
        <h2 class="rm-h2">3. 設定 wishlist（出版方）</h2>
        <p class="rm-copy">話俾買家知你而家想收咩題——撞中你 wishlist 嘅配對請求會加分排前。</p>
        <label>你嘅 site ID</label><input type="text" id="rmWishSite" placeholder="site_...">
        <label>想收嘅題（逗號分隔）</label><input type="text" id="rmWishTopics" placeholder="ai seo tools, link building">
        <button onclick="rmSetWishlist()">更新 wishlist</button>
        <div class="rm-msg" id="rmWishMsg"></div>
      </section>
    </div>
    <script>
    (function(){
      var API='https://hub.recmoment.net';
      function key(){return localStorage.getItem('rmHubKey')||''}
      function msg(id,text,ok){var e=document.getElementById(id);e.textContent=text;e.className='rm-msg '+(ok?'ok':'err')}
      function authed(){return {'content-type':'application/json','authorization':'Bearer '+key()}}
      window.rmSignup=function(){
        var email=document.getElementById('rmEmail').value.trim();
        fetch(API+'/hub/public/signup',{method:'POST',headers:{'content-type':'application/json'},body:JSON.stringify({email:email})})
        .then(function(r){return r.json()}).then(function(d){
          if(d.apiKey){localStorage.setItem('rmHubKey',d.apiKey);
            msg('rmJoinMsg','✅ 你嘅 key（只顯示一次，請即抄低）：'+d.apiKey,true);rmLoadDash();}
          else msg('rmJoinMsg',d.message||d.error||'登記失敗',!!d.already);
        }).catch(function(){msg('rmJoinMsg','網絡錯誤，稍後再試',false)});
      };
      window.rmSaveKey=function(){
        var k=document.getElementById('rmKey').value.trim();
        if(!/^rmh_[a-f0-9]{48}$/i.test(k))return msg('rmJoinMsg','key 格式唔啱（rmh_ 開頭 48 位 hex）',false);
        localStorage.setItem('rmHubKey',k);msg('rmJoinMsg','✅ 已連接',true);rmLoadDash();
      };
      window.rmLoadDash=function(){
        fetch(API+'/v1/hub/points',{headers:authed()}).then(function(r){if(!r.ok)throw 0;return r.json()})
        .then(function(d){
          document.getElementById('rmPoints').textContent=d.balance;
          document.getElementById('rmHist').textContent=d.history.length?('最近：'+d.history[0].reason):'';
          fetch(API+'/v1/hub/referral-code',{headers:authed()}).then(function(r){return r.json()})
          .then(function(r2){document.getElementById('rmRef').textContent=r2.shareUrl||r2.code});
          ['portal-dash','portal-submit','portal-wish'].forEach(function(id){document.getElementById(id).style.display=''});
        }).catch(function(){/* key 無效就唔開 dashboard */});
      };
      window.rmSubmitSite=function(){
        var body={url:document.getElementById('rmSiteUrl').value,niche:document.getElementById('rmSiteNiche').value,
          accepts:[document.getElementById('rmSiteAccepts').value]};
        var dr=document.getElementById('rmSiteDr').value;if(dr)body.dr=Number(dr);
        fetch(API+'/v1/hub/sites',{method:'POST',headers:authed(),body:JSON.stringify(body)})
        .then(function(r){return r.json()}).then(function(d){
          if(d.submitted){msg('rmSiteMsg','✅ 已提交（ID: '+d.submitted+'），賺咗 '+d.pointsEarned+' 分；審核後上架。',true);rmLoadDash();}
          else msg('rmSiteMsg',d.error||'提交失敗：'+(d.flags||[]).join(', '),false);
        }).catch(function(){msg('rmSiteMsg','網絡錯誤',false)});
      };
      window.rmSetWishlist=function(){
        var topics=document.getElementById('rmWishTopics').value.split(',').map(function(t){return t.trim()}).filter(Boolean);
        fetch(API+'/v1/hub/wishlist',{method:'POST',headers:authed(),
          body:JSON.stringify({siteId:document.getElementById('rmWishSite').value.trim(),topics:topics})})
        .then(function(r){return r.json()}).then(function(d){
          msg('rmWishMsg',d.wishlist?('✅ 已更新：'+d.wishlist.topics.join(', ')):(d.error||'失敗'),!!d.wishlist);
        }).catch(function(){msg('rmWishMsg','網絡錯誤',false)});
      };
      if(key())rmLoadDash();
    })();
    </script>
    <?php echo recmoment_page_close(); return ob_get_clean();
}
add_shortcode('recmoment_hub_portal', 'recmoment_hub_portal_shortcode');
