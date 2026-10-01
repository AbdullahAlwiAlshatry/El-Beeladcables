
/* 1. Proloder */
window.addEventListener('load', function () {
  // إخفاء الـ preloader بعد تحميل الصفحة
  var preloader = document.getElementById('preloader-active');
  if (preloader) {
    preloader.style.transition = "opacity 0.6s ease";
    preloader.style.opacity = "0";
    setTimeout(function () {
      preloader.style.display = "none";
      document.body.style.overflow = "visible";
    }, 200); // بعد انتهاء التلاشي
  }
});


/* 2. Cursor */
document.addEventListener('mousemove', moveCursor)
const cursorPointer = document.querySelector('.cursor')
function moveCursor(e) {
  cursorPointer.style.top = `${e.pageY - 4}px`
  cursorPointer.style.left = `${e.pageX - 15}px`
}


let lastScrollY = window.scrollY;
const nav = document.querySelector('nav');

window.addEventListener('scroll', () => {
  const currentScrollY = window.scrollY;

  // إذا كان المستخدم في أعلى الصفحة تماماً، نلغي التأثير ليعود شفافاً بالكامل
  if (currentScrollY <= 10) {
    nav.classList.remove('scroll-down', 'scroll-up');
    return;
  }

  // إذا كان ينزل لأسفل، أضف كلاس الإخفاء
  if (currentScrollY > lastScrollY) {
    nav.classList.add('scroll-down');
    nav.classList.remove('scroll-up');
  }
  // إذا كان يصعد لأعلى، أضف كلاس الظهور الزجاجي التدريجي
  else {
    nav.classList.add('scroll-up');
    nav.classList.remove('scroll-down');
  }

  lastScrollY = currentScrollY;
});















// ============================================
// Card Interactions + Cart System
// ============================================

document.addEventListener('DOMContentLoaded', function () {

  // --- Staggered entrance animation ---
  var cards = document.querySelectorAll('.product-card');
  cards.forEach(function (card, index) {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';

    setTimeout(function () {
      card.style.transition = 'opacity 0.5s ease, transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, index * 100);
  });

  // --- Button ripple feedback ---
  var buttons = document.querySelectorAll('.product-cart-btn');
  buttons.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      var ripple = document.createElement('span');
      var rect = btn.getBoundingClientRect();
      var size = Math.max(rect.width, rect.height);
      var x = e.clientX - rect.left - size / 2;
      var y = e.clientY - rect.top - size / 2;

      ripple.style.cssText =
        'position:absolute;width:' + size + 'px;height:' + size + 'px;' +
        'left:' + x + 'px;top:' + y + 'px;background:rgba(255,255,255,0.3);' +
        'border-radius:50%;transform:scale(0);animation:rippleEffect 0.6s ease-out;pointer-events:none;';

      btn.appendChild(ripple);
      setTimeout(function () { ripple.remove(); }, 600);
    });
  });

  // --- Tags popup ---
  var tagsPopup = document.getElementById('tagsPopup');
  var tagsPopupOverlay = document.getElementById('tagsPopupOverlay');
  var tagsPopupClose = document.getElementById('tagsPopupClose');
  var tagsPopupList = document.getElementById('tagsPopupList');

  function openTagsPopup(tags) {
    if (!tagsPopup || !tagsPopupList) return;
    tagsPopupList.innerHTML = '';
    tags.forEach(function (tag) {
      var el = document.createElement('span');
      el.className = 'tag-item';
      el.textContent = tag.Tag || tag.tag || tag;
      tagsPopupList.appendChild(el);
    });
    tagsPopup.classList.add('active');
    if (tagsPopupOverlay) tagsPopupOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeTagsPopup() {
    if (tagsPopup) tagsPopup.classList.remove('active');
    if (tagsPopupOverlay) tagsPopupOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  var infoBtns = document.querySelectorAll('.product-info-btn');
  infoBtns.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      try {
        var tags = JSON.parse(btn.getAttribute('data-tags')) || [];
        openTagsPopup(tags);
      } catch (err) {
        openTagsPopup([]);
      }
    });
  });

  if (tagsPopupClose) tagsPopupClose.addEventListener('click', closeTagsPopup);
  if (tagsPopupOverlay) tagsPopupOverlay.addEventListener('click', closeTagsPopup);

  // ============================================
  // Modal System
  // ============================================

  function openModal(id) {
    var modal = document.getElementById(id);
    if (modal) {
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeModal(id) {
    var modal = document.getElementById(id);

    if (modal) {
      modal.classList.remove('active');
      document.body.style.overflow = '';
    }
  }

  // Open via nav links
  var navLinks = document.querySelectorAll('nav ul li a');
  navLinks.forEach(function (link) {
    var text = link.textContent.trim();
    if (text === 'تواصل معنا') {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        openModal('contactModal');
      });
    }
    if (text === 'من نحن') {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        openModal('aboutModal');
      });
    }
    if (text === 'طلباتي') {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        renderSavedOrders();
        openModal('savedOrdersModal');
      });
    }
    // Close mobile menu on click
    link.addEventListener('click', function () {
      var check = document.getElementById('check');
      if (check && check.checked) check.checked = false;
    });
  });

  // Close buttons
  var closeButtons = document.querySelectorAll('.modal-close');
  closeButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      closeModal(btn.getAttribute('data-close'));
    });
  });

  // Overlay click
  var overlays = document.querySelectorAll('.modal-overlay');
  overlays.forEach(function (overlay) {
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) {
        closeModal(overlay.id);
      }
    });
  });

  // Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {

      overlays.forEach(function (o) {
        if (o.classList.contains('active')) {
          closeModal(o.id);
        }
      });

      closeCartDrawer();
      closeTagsPopup();

      document.body.style.overflow = '';
    }
  });

  // Contact form
  var contactForm = document.getElementById('contactForm');
  var orderForm = document.getElementById('orderForm');
  var pendingCustomer = null;

  if (contactForm) {
    contactForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      var wrapper = document.getElementById('contactFormWrapper');
      var success = document.getElementById('contactSuccess');

      try {
        var formData = new FormData(contactForm);

        var response = await fetch(contactForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json'
          }
        });

        if (wrapper) wrapper.style.display = 'none';
        if (success) success.classList.add('show');

        setTimeout(function () {
          closeModal('contactModal');
          contactForm.reset();

          if (wrapper) wrapper.style.display = '';
          if (success) success.classList.remove('show');
        }, 2800);

      } catch (error) {
        console.error(error);
        alert('حدث خطأ أثناء إرسال الرسالة');
      }
    });
  }

  if (orderForm) {
    orderForm.addEventListener('submit', async function (e) {
      e.preventDefault();

      // لا يوجد عميل مؤقت = لا يوجد طلب صالح للإرسال
      if (!pendingCustomer) {
        alert('يرجى إدخال معلومات العميل أولاً');
        showCustomerInfoModal();
        return;
      }

      var cart = getCart();

      if (!cart || cart.length === 0) {
        alert('السلة فارغة');
        return;
      }

      var sendBtn = document.getElementById('receiptSendBtn');

      try {
        // منع الضغط المتكرر
        if (sendBtn) {
          sendBtn.disabled = true;
        }

        // ==========================================
        // 1. إنشاء بيانات الطلب
        // ==========================================
        var orderNo = generateOrderNo();
        var date = formatDate();

        var totalQty = cart.reduce(function (sum, item) {
          return sum + item.qty;
        }, 0);

        document.getElementById('receiptOrderNo').textContent = orderNo;
        document.getElementById('receiptDate').textContent = date;
        document.getElementById('receiptTotalItems').textContent = totalQty;

        // ==========================================
        // 2. بيانات العميل
        // ==========================================
        var customer = {
          name: pendingCustomer.name,
          phone: pendingCustomer.phone,
          shop: pendingCustomer.shop,
          address: pendingCustomer.address
        };

        // ==========================================
        // 3. إنشاء HTML الخاص بالـ PDF
        // ==========================================
        var pdfHtml = buildOrderPdfHtml(
          cart,
          orderNo,
          date,
          totalQty,
          customer
        );

        // ==========================================
        // 4. تجهيز بيانات المنتجات
        // ==========================================
        var items = cart.map(function (item) {
          return {
            name: item.name,
            title: item.title || '',
            type: item.type || '',
            picture: item.picture || '',
            tags: item.tags || [],
            qty: item.qty
          };
        });

        // ==========================================
        // 5. تعديل جوهري: حزم البيانات داخل FormData (مثل كود الـ Contact الناجح)
        // ==========================================
        var formData = new FormData(orderForm);

        // حشر المتغيرات المولدة برمجياً داخل الـ FormData
        formData.append('orderNo', orderNo);
        formData.append('date', date);
        formData.append('totalItems', totalQty);
        formData.append('pdfHtml', pdfHtml);

        // حشر معلومات العميل بالتفصيل
        formData.append('customerName', customer.name);
        formData.append('customerPhone', customer.phone);
        formData.append('customerShop', customer.shop || '');
        formData.append('customerAddress', customer.address);

        // حشر مصفوفة المنتجات كـ String ليتم فكها برمجياً في لارافيل بسهولة
        formData.append('items', JSON.stringify(items));

        // ==========================================
        // 6. POST إلى Laravel بنظام الـ Multipart الآمن
        // ==========================================

        var response = await fetch(orderForm.action, {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json'
          }
        });

        // إذا لارافيل أعاد خطأ HTTP
        if (!response.ok) {
          var errorText = await response.text();

          console.error('Laravel response:', errorText);

          throw new Error(
            'Server error: ' +
            response.status +
            '\n' +
            errorText
          );
        }

        var result = await response.json();

        // ==========================================
        // 7. نجاح العملية
        // ==========================================
        var orders = getOrders();

        orders.unshift({
          orderNo: orderNo,
          date: date,
          totalItems: totalQty,
          items: cart.slice(),
          customer: {
            name: customer.name,
            phone: customer.phone,
            shop: customer.shop,
            address: customer.address
          }
        });

        saveOrders(orders);

        // تفريغ السلة محلّياً
        saveCart([]);
        renderCart();

        // إظهار نجاح الإرسال في الواجهة
        showReceiptSuccess();

        // انتهت العملية بنجاح
        pendingCustomer = null;

      } catch (error) {
        console.error('Order error:', error);
        alert('حدث خطأ أثناء إرسال الطلب، يرجى المحاولة مرة أخرى.');
      } finally {
        // إعادة الزر لحالته الطبيعية
        if (sendBtn) {
          sendBtn.disabled = false;
        }
      }
    });
  }

  // ============================================
  // Cart System
  // ============================================

  var CART_KEY = 'belaad_cart';
  var ORDERS_KEY = 'belaad_orders';

  function getCart() {
    try {
      var raw = localStorage.getItem(CART_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch (err) {
      return [];
    }
  }

  function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
  }

  function getOrders() {
    try {
      var raw = localStorage.getItem(ORDERS_KEY);
      return raw ? JSON.parse(raw) : [];
    } catch (err) {
      return [];
    }
  }

  function saveOrders(orders) {
    localStorage.setItem(ORDERS_KEY, JSON.stringify(orders));
  }

  function generateOrderNo() {
    var d = new Date();
    return 'ORD-' + d.getFullYear() + String(d.getMonth() + 1).padStart(2, '0') +
      String(d.getDate()).padStart(2, '0') + '-' + String(d.getHours()).padStart(2, '0') +
      String(d.getMinutes()).padStart(2, '0') + String(d.getSeconds()).padStart(2, '0');
  }

  function formatDate() {
    var d = new Date();
    var months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
      'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
    return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear() + ' - ' +
      String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
  }

  // --- Add to cart ---
  var addButtons = document.querySelectorAll('.cart-add-btn');
  addButtons.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var id = btn.getAttribute('data-product-id');
      var name = btn.getAttribute('data-product-name');
      var title = btn.getAttribute('data-product-title');
      var type = btn.getAttribute('data-product-type');
      var picture = btn.getAttribute('data-product-picture');
      var tagsRaw = btn.getAttribute('data-product-tags');
      var tags = [];
      try { tags = JSON.parse(tagsRaw) || []; } catch (e) { tags = []; }
      var tagNames = tags.map(function (t) { return t.Tag || t.tag || t; }).filter(Boolean);

      var cart = getCart();
      var existing = cart.find(function (item) { return item.id === id; });

      if (existing) {
        existing.qty += 1;
      } else {
        cart.push({
          id: id,
          name: name,
          title: title,
          type: type,
          picture: picture,
          tags: tagNames,
          qty: 1
        });
      }

      saveCart(cart);
      renderCart();
      bumpBadge();

      // Fly-to-cart animation
      flyToCart(btn);
    });
  });

  function bumpBadge() {
    var badge = document.getElementById('bottomBarBadge');
    if (!badge) return;
    badge.classList.remove('bump');
    void badge.offsetWidth;
    badge.classList.add('bump');
  }

  function flyToCart(sourceBtn) {
    var fab = document.getElementById('bottomBarCart');
    if (!fab || !sourceBtn) return;
    var sourceRect = sourceBtn.getBoundingClientRect();
    var fabRect = fab.getBoundingClientRect();

    var flyer = document.createElement('div');
    flyer.style.cssText =
      'position:fixed;width:30px;height:30px;border-radius:50%;' +
      'background:linear-gradient(135deg,#ff6a00,#aa3b22);z-index:99999;' +
      'left:' + (sourceRect.left + sourceRect.width / 2 - 15) + 'px;' +
      'top:' + (sourceRect.top + sourceRect.height / 2 - 15) + 'px;' +
      'transition:all 0.6s cubic-bezier(0.175,0.885,0.32,1.275);' +
      'pointer-events:none;box-shadow:0 4px 12px rgba(255,106,0,0.4);';

    document.body.appendChild(flyer);

    requestAnimationFrame(function () {
      flyer.style.left = (fabRect.left + fabRect.width / 2 - 15) + 'px';
      flyer.style.top = (fabRect.top + fabRect.height / 2 - 15) + 'px';
      flyer.style.transform = 'scale(0.3)';
      flyer.style.opacity = '0.3';
    });

    setTimeout(function () {
      flyer.remove();
      fab.style.transform = 'scale(1.2)';
      setTimeout(function () { fab.style.transform = ''; }, 200);
    }, 600);
  }

  // --- Render cart ---
  function renderCart() {
    var cart = getCart();
    var body = document.getElementById('cartDrawerBody');
    var footer = document.getElementById('cartDrawerFooter');
    var empty = document.getElementById('cartEmpty');
    var badge = document.getElementById('bottomBarBadge');
    var totalItems = document.getElementById('cartTotalItems');

    var totalQty = cart.reduce(function (sum, item) { return sum + item.qty; }, 0);

    if (badge) {
      badge.textContent = totalQty;
      badge.classList.toggle('hidden', totalQty === 0);
    }

    if (totalItems) totalItems.textContent = totalQty;

    if (cart.length === 0) {
      if (empty) empty.style.display = 'block';
      if (footer) footer.style.display = 'none';
      // Remove all cart items except empty placeholder
      var itemEls = body.querySelectorAll('.cart-item');
      itemEls.forEach(function (el) { el.remove(); });
      return;
    }

    if (empty) empty.style.display = 'none';
    if (footer) footer.style.display = 'block';

    // Remove existing items
    var existing = body.querySelectorAll('.cart-item');
    existing.forEach(function (el) { el.remove(); });

    cart.forEach(function (item) {
      var el = document.createElement('div');
      el.className = 'cart-item';
      el.innerHTML =
        '<div class="cart-item-img">' +
        '<img src="/assets/uploads/' + (item.picture || '') + '" alt="" onerror="this.style.display=\'none\'">' +
        '</div>' +
        '<div class="cart-item-info">' +
        '<div class="cart-item-name">' + escapeHtml(item.name) + '</div>' +
        '<div class="cart-item-cat">' + escapeHtml(item.title || '') + '</div>' +
        '<div class="cart-item-controls">' +
        '<button class="cart-qty-btn cart-qty-minus" data-name="' + escapeAttr(item.name) + '">&minus;</button>' +
        '<span class="cart-qty-value">' + item.qty + '</span>' +
        '<button class="cart-qty-btn cart-qty-plus" data-name="' + escapeAttr(item.name) + '">+</button>' +
        '</div>' +
        '</div>' +
        '<button class="cart-item-remove" data-name="' + escapeAttr(item.name) + '">' +
        '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
        'stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
        '<polyline points="3 6 5 6 21 6"/>' +
        '<path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>' +
        '</svg>' +
        '</button>';

      body.appendChild(el);
    });

    // Bind qty buttons
    body.querySelectorAll('.cart-qty-plus').forEach(function (b) {
      b.addEventListener('click', function () {
        changeQty(b.getAttribute('data-name'), 1);
      });
    });
    body.querySelectorAll('.cart-qty-minus').forEach(function (b) {
      b.addEventListener('click', function () {
        changeQty(b.getAttribute('data-name'), -1);
      });
    });
    body.querySelectorAll('.cart-item-remove').forEach(function (b) {
      b.addEventListener('click', function () {
        removeItem(b.getAttribute('data-name'));
      });
    });
  }

  function changeQty(name, delta) {
    var cart = getCart();
    var item = cart.find(function (i) { return i.name === name; });
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) {
      cart = cart.filter(function (i) { return i.name !== name; });
    }
    saveCart(cart);
    renderCart();
  }

  function removeItem(name) {
    var cart = getCart().filter(function (i) { return i.name !== name; });
    saveCart(cart);
    renderCart();
  }

  // --- Cart drawer open/close ---
  var cartFab = document.getElementById('bottomBarCart');
  var cartDrawer = document.getElementById('cartDrawer');
  var cartDrawerOverlay = document.getElementById('cartDrawerOverlay');
  var cartDrawerClose = document.getElementById('cartDrawerClose');

  function openCartDrawer() {
    if (cartDrawer) cartDrawer.classList.add('active');
    if (cartDrawerOverlay) cartDrawerOverlay.classList.add('active');
  }

  function closeCartDrawer() {
    if (cartDrawer) cartDrawer.classList.remove('active');
    if (cartDrawerOverlay) cartDrawerOverlay.classList.remove('active');
  }

  if (cartFab) cartFab.addEventListener('click', openCartDrawer);
  if (cartDrawerClose) cartDrawerClose.addEventListener('click', closeCartDrawer);
  if (cartDrawerOverlay) cartDrawerOverlay.addEventListener('click', closeCartDrawer);

  // --- Cart actions ---
  var cartViewReceiptBtn = document.getElementById('cartViewReceiptBtn');
  if (cartViewReceiptBtn) {
    cartViewReceiptBtn.addEventListener('click', function () {
      closeCartDrawer();
      showReceipt();
    });
  }

  var cartSendBtn = document.getElementById('cartSendBtn');
  if (cartSendBtn) {
    cartSendBtn.addEventListener('click', function () {
      closeCartDrawer();
      showCustomerInfoModal();
    });
  }

  var cartCancelBtn = document.getElementById('cartCancelBtn');
  if (cartCancelBtn) {
    cartCancelBtn.addEventListener('click', function () {
      if (confirm('هل أنت متأكد من إفراغ السلة؟')) {
        saveCart([]);
        renderCart();
      }
    });
  }

  // --- Receipt ---
  function showReceipt() {
    var cart = getCart();

    if (cart.length === 0) return;

    var totalQty = cart.reduce(function (s, i) {
      return s + i.qty;
    }, 0);

    document.getElementById('receiptOrderNo').textContent = generateOrderNo();
    document.getElementById('receiptDate').textContent = formatDate();
    document.getElementById('receiptTotalItems').textContent = totalQty;

    var itemsContainer = document.getElementById('receiptItems');
    itemsContainer.innerHTML = '';

    cart.forEach(function (item) {
      var el = document.createElement('div');
      el.className = 'receipt-item';
      el.innerHTML =
        '<div class="receipt-item-info">' +
        '<div class="receipt-item-img">' +
        '<img src="/assets/uploads/' + (item.picture || '') + '" alt="" onerror="this.style.display=\'none\'">' +
        '</div>' +
        '<div>' +
        '<div class="receipt-item-name">' + escapeHtml(item.name) + '</div>' +
        '<div class="receipt-item-cat">' + escapeHtml(item.title || '') + '</div>' +
        '</div>' +
        '</div>' +
        '<div class="receipt-item-qty">×' + item.qty + '</div>';
      itemsContainer.appendChild(el);
    });

    // Show receipt content, hide success
    var content = document.getElementById('receiptContent');
    var success = document.getElementById('receiptSuccess');
    if (content) content.style.display = '';
    if (success) success.classList.remove('show');

    openModal('receiptModal');
  }



  // ============================================
  // Customer Info Modal + Order Submission
  // ============================================

  function showCustomerInfoModal() {
    var cart = getCart();

    if (cart.length === 0) return;

    var form = document.getElementById('customerInfoForm');

    if (form) {
      form.reset();
    }

    // بداية عملية جديدة
    pendingCustomer = null;

    openModal('customerInfoModal');
  }

  var customerInfoSubmit = document.getElementById('customerInfoSubmit');
  if (customerInfoSubmit) {
    customerInfoSubmit.addEventListener('click', function () {

      var customerInfoForm = document.getElementById('customerInfoForm');

      // تشغيل validation الخاص بـ HTML
      if (customerInfoForm && !customerInfoForm.reportValidity()) {
        return;
      }

      var custName =
        (document.getElementById('custName') || {}).value || '';

      var custPhone =
        (document.getElementById('custPhone') || {}).value || '';

      var custShop =
        (document.getElementById('custShop') || {}).value || '';

      var custAddress =
        (document.getElementById('custAddress') || {}).value || '';

      // تخزين مؤقت فقط
      pendingCustomer = {
        name: custName.trim(),
        phone: custPhone.trim(),
        shop: custShop.trim(),
        address: custAddress.trim()
      };

      // تجهيز بيانات الفاتورة للعرض
      showReceipt();

      // الانتقال من Modal معلومات العميل إلى Modal الفاتورة
      closeModal('customerInfoModal');
      openModal('receiptModal');
    });
  }


  function showReceiptSuccess() {
    var content = document.getElementById('receiptContent');
    var success = document.getElementById('receiptSuccess');

    if (content) content.style.display = 'none';

    if (success) success.classList.add('show');

    setTimeout(function () {
      closeModal('receiptModal');

      if (content) content.style.display = '';

      if (success) success.classList.remove('show');
    }, 2800);
  }

  function buildOrderPdfHtmlWithOutCustomerInfo(cart, orderNo, date, totalQty) {
    var rows = '';
    cart.forEach(function (item, i) {
      var tagsHtml = (item.tags || []).map(function (t) {
        return '<span style="display:inline-block;background:#fff4ec;color:#ff6a00;border:1px solid rgba(255,106,0,0.2);border-radius:10px;padding:3px 10px;font-size:11px;margin:2px;">' + escapeHtml(t) + '</span>';
      }).join('');
      if (!tagsHtml) tagsHtml = '<span style="color:#aaa;font-size:11px;">لا توجد</span>';

      rows +=
        '<tr>' +
        '<td style="text-align:center;padding:10px 8px;">' + (i + 1) + '</td>' +
        '<td style="padding:10px 8px;text-align:center;">' +
        (item.picture ? '<img src="/assets/uploads/' + escapeAttr(item.picture) + '" style="width:55px;height:55px;object-fit:cover;border-radius:6px;" onerror="this.style.display=\'none\'" />' : '') +
        '</td>' +
        '<td style="padding:10px 8px;font-weight:700;">' + escapeHtml(item.name) + '</td>' +
        '<td style="padding:10px 8px;color:#ff6a00;font-size:12px;">' + escapeHtml(item.type || '') + '</td>' +
        '<td style="padding:10px 8px;font-size:11px;max-width:200px;">' + tagsHtml + '</td>' +
        '<td style="text-align:center;padding:10px 8px;font-weight:800;font-size:15px;color:#ff6a00;">' + item.qty + '</td>' +
        '</tr>';
    });

    return (
      '<html dir="rtl"><head><title>طلبية - ' + escapeHtml(orderNo) + '</title>' +
      '<meta charset="UTF-8">' +
      '<style>' +

      '@font-face { font-family: "Logo Font"; src: url("alfont_com_MarhabanArabicDEMO-Bold.otf") format("opentype");};' +
      '*{font-family: "Ramis Arabic", sans-serif !important;}' +

      /* تم إضافة خواص إجبار المتصفح على طباعة الألوان هنا */
      'body{font-family: "Cairo", sans-serif; padding:30px;direction:rtl;color:#18181b; -webkit-print-color-adjust: exact; print-color-adjust: exact;}' +
      'h1{text-align:center;color:#ff6a00;margin:0 0 4px;font-size:24px;}' +
      '.sub{text-align:center;color:#999;margin-bottom:20px;font-size:13px;}' +
      'table{width:100%;border-collapse:collapse;margin-bottom:16px;}' +
      'th{background:#ff6a00;color:#fff;padding:10px 8px;font-size:12px;text-align:right;}' +
      'td{border-bottom:1px solid #eee;font-size:13px;}' +
      '.total-box{display:block;clear:both;background:#18181b;color:#fff;padding:14px 20px;border-radius:10px;font-size:16px;font-weight:800;}' +
      '.total-box .val{color:#ff6a00; position:relative; top:3px; font-size:19px; }' +
      '.note{text-align:center;font-size:11px;color:#aaa;margin-top:20px;}' +
      '@media print{body{padding:15px;}}' +
      '</style></head><body>' +
      '<h1>البلاد للكهربائيات</h1>' +
      '<p class="sub">' + escapeHtml(orderNo) + ' <br> ' + escapeHtml(date) + '</p>' +
      '<table>' +
      '<thead><tr>' +
      '<th style="width:30px;">#</th>' +
      '<th style="width:70px;">صورة</th>' +
      '<th>اسم المنتج</th>' +
      '<th>التصنيف</th>' +
      '<th>الكلمات المفتاحية</th>' +
      '<th style="width:50px;">الكمية</th>' +
      '</tr></thead>' +
      '<tbody>' + rows + '</tbody>' +
      '</table>' +
      '<div class="total-box"><span>إجمالي عدد القطع</span><span class="val">&nbsp;' + totalQty + '</span></div>' +
      '<p class="note">تم إنشاء هذه الطلبية من موقع البلاد للكهربائيات</p>' +
      '</body></html>'
    );
  }

  function buildOrderPdfHtml(cart, orderNo, date, totalQty, customer) {
    var rows = '';
    cart.forEach(function (item, i) {
      var tagsHtml = (item.tags || []).map(function (t) {
        return '<span style="display:inline-block;background:#fff4ec;color:#ff6a00;border:1px solid rgba(255,106,0,0.2);border-radius:10px;padding:3px 10px;font-size:11px;margin:2px;">' + escapeHtml(t) + '</span>';
      }).join('');
      if (!tagsHtml) tagsHtml = '<span style="color:#aaa;font-size:11px;">لا توجد</span>';

      rows +=
        '<tr>' +
        '<td style="text-align:center;padding:10px 8px;">' + (i + 1) + '</td>' +
        '<td style="padding:10px 8px;text-align:center;">' +
        (item.picture ? '<img src="/assets/uploads/' + escapeAttr(item.picture) + '" style="width:55px;height:55px;object-fit:cover;border-radius:6px;" onerror="this.style.display=\'none\'" />' : '') +
        '</td>' +
        '<td style="padding:10px 8px;font-weight:700;">' + escapeHtml(item.name) + '</td>' +
        '<td style="padding:10px 8px;color:#ff6a00;font-size:12px;">' + escapeHtml(item.type || '') + '</td>' +
        '<td style="padding:10px 8px;font-size:11px;max-width:200px;">' + tagsHtml + '</td>' +
        '<td style="text-align:center;padding:10px 8px;font-weight:800;font-size:15px;color:#ff6a00;">' + item.qty + '</td>' +
        '</tr>';
    });

    return (
      '<html dir="rtl"><head><title>طلبية - ' + escapeHtml(orderNo) + '</title>' +
      '<meta charset="UTF-8">' +
      '<style>' +

      '@font-face { font-family: "Logo Font"; src: url("alfont_com_MarhabanArabicDEMO-Bold.otf") format("opentype");};' +
      '*{font-family: "Ramis Arabic", sans-serif !important;}' +

      'body{font-family: "Cairo", sans-serif; padding:30px;direction:rtl;color:#18181b;}' +
      'h1{text-align:center;color:#ff6a00;margin:0 0 4px;font-size:24px;}' +
      '.sub{text-align:center;color:#999;margin-bottom:20px;font-size:13px;}' +
      '.cust-box{background:#f9f9f9;border:1px solid #eee;border-radius:10px;padding:16px 20px;margin-bottom:20px;}' +
      '.cust-box h3{margin:0 0 12px;font-size:15px;color:#ff6a00;border-bottom:1px solid #eee;padding-bottom:8px;}' +
      '.cust-row{display:block;clear:both;margin:6px 0;font-size:13px;}' +
      '.cust-row .lbl{color:#999;font-weight:600;}' +
      '.cust-row .val{color:#18181b;font-weight:700;}' +
      'table{width:100%;border-collapse:collapse;margin-bottom:16px;}' +
      'th{background:#ff6a00;color:#fff;padding:10px 8px;font-size:12px;text-align:right;}' +
      'td{border-bottom:1px solid #eee;font-size:13px;}' +
      '.total-box{display:block;clear:both;background:#18181b;color:#fff;padding:14px 20px;border-radius:10px;font-size:16px;font-weight:800;}' +
      '.total-box .val{color:#ff6a00; position:relative; top:3px; font-size:19px; }' +
      '.note{text-align:center;font-size:11px;color:#aaa;margin-top:20px;}' +
      '@media print{body{padding:15px;}}' +
      '</style></head><body>' +
      '<h1>البلاد للكهربائيات</h1>' +
      '<p class="sub">' + escapeHtml(orderNo) + ' <br> ' + escapeHtml(date) + '</p>' +
      '<div class="cust-box">' +
      '<h3>معلومات العميل</h3>' +
      '<div class="cust-row"><span class="lbl">الاسم:&nbsp;</span><span class="val">' + escapeHtml(customer.name) + '</span></div>' +
      '<div class="cust-row"><span class="lbl">الهاتف:&nbsp;</span><span class="val">' + escapeHtml(customer.phone) + '</span></div>' +
      (customer.shop ? '<div class="cust-row"><span class="lbl">المحل:&nbsp;</span><span class="val">' + escapeHtml(customer.shop) + '</span></div>' : '') +
      '<div class="cust-row" style="flex-direction:column;"><span class="lbl" style="margin-bottom:4px;">العنوان التفصيلي:&nbsp;</span><span class="val" style="text-align:right;width:100%;">' + escapeHtml(customer.address) + '</span></div>' +
      '</div>' +
      '<table>' +
      '<thead><tr>' +
      '<th style="width:30px;">#</th>' +
      '<th style="width:70px;">صورة</th>' +
      '<th>اسم المنتج</th>' +
      '<th>التصنيف</th>' +
      '<th>الكلمات المفتاحية</th>' +
      '<th style="width:50px;">الكمية</th>' +
      '</tr></thead>' +
      '<tbody>' + rows + '</tbody>' +
      '</table>' +
      '<div class="total-box"><span>إجمالي عدد القطع</span><span class="val">&nbsp;' + totalQty + '</span></div>' +
      '<p class="note">تم إنشاء هذه الطلبية من موقع البلاد للكهربائيات</p>' +
      '</body></html>'
    );
  }

  // --- Receipt print ---
  var receiptPrintBtn = document.getElementById('receiptPrintBtn');
  if (receiptPrintBtn) {
    receiptPrintBtn.addEventListener('click', function () {

      var receiptContent = document.getElementById('receiptContent');
      if (!receiptContent) return;


      var cart = getCart();

      if (!cart || cart.length === 0) { alert('السلة فارغة'); return; }

      var orderNo = generateOrderNo();

      var date = formatDate();

      var totalQty = cart.reduce(function (sum, item) { return sum + item.qty; }, 0);

      document.getElementById('receiptTotalItems').textContent = totalQty;


      var win = window.open('', '_blank', 'width=400,height=600');
      var htmlPrint = buildOrderPdfHtmlWithOutCustomerInfo(cart, orderNo, date, totalQty);
      win.document.write(htmlPrint);
      win.document.close();
      win.focus();
      setTimeout(function () { win.print(); }, 300);
    });
  }

  // --- Saved Orders ---
  function renderSavedOrders() {
    var orders = getOrders();
    var container = document.getElementById('savedOrdersList');
    if (!container) return;

    if (orders.length === 0) {
      container.innerHTML = '<div class="saved-orders-empty">لا توجد طلبات محفوظة بعد</div>';
      return;
    }

    container.innerHTML = '';
    orders.forEach(function (order, index) {
      var el = document.createElement('div');
      el.className = 'saved-order-card';

      var itemsHtml = '';
      order.items.forEach(function (item) {
        itemsHtml +=
          '<div class="saved-order-item-line">' +
          '<span>' + escapeHtml(item.name) + '</span>' +
          '<span>×' + item.qty + '</span>' +
          '</div>';
      });

      el.innerHTML =
        '<div class="saved-order-header">' +
        '<span class="saved-order-no">' + escapeHtml(order.orderNo) + '</span>' +
        '<span class="saved-order-date">' + escapeHtml(order.date) + '</span>' +
        '</div>' +
        '<div class="saved-order-items">' + itemsHtml + '</div>' +
        '<div class="saved-order-footer">' +
        '<span class="saved-order-total">إجمالي: ' + order.totalItems + ' قطعة</span>' +
        '<button class="saved-order-view-btn" data-index="' + index + '">عرض الفاتورة</button>' +
        '</div>';

      container.appendChild(el);
    });

    // Bind view buttons
    container.querySelectorAll('.saved-order-view-btn').forEach(function (b) {
      b.addEventListener('click', function () {
        var idx = parseInt(b.getAttribute('data-index'), 10);
        showSavedReceipt(idx);
      });
    });
  }

  function showSavedReceipt(index) {
    var orders = getOrders();
    var order = orders[index];
    if (!order) return;

    closeModal('savedOrdersModal');

    document.getElementById('receiptOrderNo').textContent = order.orderNo;
    document.getElementById('receiptDate').textContent = order.date;
    document.getElementById('receiptTotalItems').textContent = order.totalItems;

    var itemsContainer = document.getElementById('receiptItems');
    itemsContainer.innerHTML = '';

    order.items.forEach(function (item) {
      var el = document.createElement('div');
      el.className = 'receipt-item';
      el.innerHTML =
        '<div class="receipt-item-info">' +
        '<div class="receipt-item-img">' +
        '<img src="/assets/uploads/' + (item.picture || '') + '" alt="" onerror="this.style.display=\'none\'">' +
        '</div>' +
        '<div>' +
        '<div class="receipt-item-name">' + escapeHtml(item.name) + '</div>' +
        '<div class="receipt-item-cat">' + escapeHtml(item.title || '') + '</div>' +
        '</div>' +
        '</div>' +
        '<div class="receipt-item-qty">×' + item.qty + '</div>';
      itemsContainer.appendChild(el);
    });

    var content = document.getElementById('receiptContent');
    var success = document.getElementById('receiptSuccess');
    if (content) content.style.display = '';
    if (success) success.classList.remove('show');

    openModal('receiptModal');
  }

  // --- Utility functions ---
  function escapeHtml(str) {
    if (!str) return '';
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }

  function escapeAttr(str) {
    if (!str) return '';
    return str.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
  }

  function cancelPendingOrder() {
    pendingCustomer = null;
  }

  // ============================================
  // Mobile Bottom Bar
  // ============================================

  var bottomBarCart = document.getElementById('bottomBarCart');
  if (bottomBarCart) {
    bottomBarCart.addEventListener('click', function () {
      openCartDrawer();
    });
  }

  // Sync bottom bar badge with cart badge
  function updateBottomBarBadge() {
    var cart = getCart();
    var totalQty = cart.reduce(function (s, i) { return s + i.qty; }, 0);
    var badge = document.getElementById('bottomBarBadge');
    if (badge) {
      badge.textContent = totalQty;
      badge.classList.toggle('hidden', totalQty === 0);
    }
  }

  // Override renderCart to also update bottom bar badge
  var originalRenderCart = renderCart;
  renderCart = function () {
    originalRenderCart();
    updateBottomBarBadge();
  };

  // --- PDF / Print product list ---
  var bottomBarPdf = document.getElementById('bottomBarPdf');
  if (bottomBarPdf) {
    bottomBarPdf.addEventListener('click', function () {
      printProductList();
    });
  }

  function printProductList() {
    var cards = document.querySelectorAll('.product-card');
    if (cards.length === 0) return;

    var rows = '';
    cards.forEach(function (card, i) {
      var name = card.getAttribute('data-product-name') || '';
      var title = card.getAttribute('data-product-title') || '';
      var img = card.querySelector('.product-image-wrap img') || '';
      var imgSrc = img ? img.src : '';
      var type = card.getAttribute('data-product-type');



      rows +=
        '<tr>' +
        '<td style="text-align:center;padding:8px;">' + (i + 1) + '</td>' +

        '<td style="padding:8px;">' +
        (imgSrc ? '<img src="' + imgSrc + '" style="width:50px;height:50px;object-fit:cover;border-radius:6px;" />' : '') +
        '</td>' +

        '<td style="padding:8px;">' +
        '<div style="font-weight:bold;">' + escapeHtml(name) + '</div>' +
        '<div style="font-size:11px;color:#888;margin-top:4px;">' +
        escapeHtml(title) +
        '</div>' +
        '</td>' +

        '<td style="padding:8px;color:#ff6a00;font-size:12px;">' +
        escapeHtml(type) +
        '</td>' +

        '</tr>';
    });

    var win = window.open('', '_blank', 'width=800,height=600');
    win.document.write(
      '<html dir="rtl"><head><title>قائمة المنتجات - البلاد للكهربائيات</title>' +
      '<style>' +
      'body{font-family:Arial,sans-serif;padding:30px;direction:rtl;color:#18181b;}' +
      'h1{text-align:center;color:#ff6a00;margin-bottom:6px;}' +
      '.sub{text-align:center;color:#999;margin-bottom:24px;font-size:13px;}' +
      'table{width:100%;border-collapse:collapse;}' +
      'th{background:#f4f4f5;padding:10px 8px;font-size:13px;text-align:right;border-bottom:2px solid #e4e4e7;}' +
      'td{border-bottom:1px solid #f0f0f0;font-size:13px;}' +
      '.footer-note{text-align:center;font-size:11px;color:#aaa;margin-top:24px;}' +
      '@media print{.no-print{display:none;}}' +
      '</style></head><body>' +
      '<h1>البلاد للكهربائيات</h1>' +
      '<p class="sub">قائمة المنتجات - ' + new Date().toLocaleDateString('ar-EG') + '</p>' +
      '<table>' +
      '<thead><tr>' +
      '<th style="width:40px;">#</th>' +
      '<th style="width:70px;">صورة</th>' +
      '<th>اسم المنتج</th>' +
      '<th>التصنيف</th>' +
      '</tr></thead>' +
      '<tbody>' + rows + '</tbody>' +
      '</table>' +
      '<p class="footer-note">تم إنشاء هذه القائمة من موقع البلاد للكهربائيات</p>' +
      '</body></html>'
    );
    win.document.close();
    win.focus();
    setTimeout(function () { win.print(); }, 400);
  }

  updateBottomBarBadge();


  // ============================================
  // Metal Prices Modal
  // ============================================

  var metalsData = [];

  function refreshMetalPrices() {
    if (metalsRefreshBtn) metalsRefreshBtn.classList.add('spinning');

    fetch('/api/metal-prices')
      .then(function (response) {
        // 💡 إذا انهار السيرفر (500)، نقرأ نص الخطأ الممرر منه بدلاً من الانهيار المفاجئ
        if (!response.ok) {
          return response.json().then(function (errText) {
            throw new Error(errText.message || 'خطأ غير معروف في السيرفر');
          });
        }
        return response.json();
      })
      .then(function (result) {
        if (result.success && result.metals) {
          metalsData = result.metals;
          renderMetals();
        }
      })
      .catch(function (err) {
        // 🔴 هنا ستظهر لك الرسالة الحقيقية المسببة للمشكلة داخل لارافيل باللون الأحمر
        console.error('الاستثناء الفعلي من لارافيل:', err.message);
      })
      .finally(function () {
        if (metalsRefreshBtn) {
          setTimeout(function () { metalsRefreshBtn.classList.remove('spinning'); }, 400);
        }
      });
  }



  refreshMetalPrices();

  var metalsOverlay = document.getElementById('metalsOverlay');
  var metalsClose = document.getElementById('metalsClose');
  var metalsGrid = document.getElementById('metalsGrid');
  var metalsUpdated = document.getElementById('metalsUpdated');
  var metalsRefreshBtn = document.getElementById('metalsRefreshBtn');
  var bottomBarMetals = document.getElementById('bottomBarMetals');

  function openMetalsModal() {
    renderMetals();
    if (metalsOverlay) metalsOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeMetalsModal() {
    if (metalsOverlay) metalsOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  // 🛠️ تم تصحيح الدالة بالكامل وتنسيق الأرقام هنا
  function formatPrice(price) {
    if (price >= 1000) {
      return price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    return price.toFixed(2);
  }

  function formatChange(change) {
    if (change > 0) {
      return { text: '+' + change.toFixed(2), class: 'up', arrow: '▲' };
    } else if (change < 0) {
      return { text: change.toFixed(2), class: 'down', arrow: '▼' };
    }
    return { text: '0.00', class: 'flat', arrow: '▬' };
  }

  function renderMetals() {
    if (!metalsGrid) return;
    metalsGrid.innerHTML = '';

    // 1️⃣ فحص حماية (Guard Clause) لمنع الانهيار إذا كانت المصفوفة فارغة أو Null
    if (!metalsData || !Array.isArray(metalsData) || metalsData.length === 0) {
      metalsGrid.innerHTML = '<div style="color:#fff; text-align:center; width:100%; padding: 20px;">جاري تحميل الأسعار الحالية...</div>';
      return; // الخروج فوراً لحين وصول بيانات الـ API
    }

    metalsData.forEach(function (metal) {
      var ch = formatChange(metal.change);
      var el = document.createElement('div');
      el.className = 'metal-card';
      el.innerHTML =
        '<div class="metal-icon ' + metal.iconClass + '">' + metal.icon + '</div>' +
        '<div class="metal-info">' +
        '<div class="metal-name">' + metal.name + '</div>' +
        '<div class="metal-unit">السعر / ' + metal.unit + '</div>' +
        '<div class="metal-change ' + ch.class + '">' +
        '<span>' + ch.arrow + '</span>' +
        '<span>' + ch.text + '</span>' +
        '</div>' +
        '</div>' +
        '<div style="text-align:left;">' +
        // 2️⃣ تم تعديل السعر هنا ليمر عبر دالة التنسيق لضبط الكسور العشرية والآلاف
        '<div class="metal-price">' + formatPrice(metal.price) + '</div>' +
        '<div class="metal-price-label">USD</div>' +
        '</div>';
      metalsGrid.appendChild(el);
    });

    if (metalsUpdated) {
      var now = new Date();
      // تم الإبقاء على صيغة التاريخ en-US بناءً على طلبك لتعرض الشهور اللاتينية (أكتوبر، يناير إلخ)
      var dateStr = now.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });

      metalsUpdated.textContent = 'آخر تحديث - ' + dateStr;
    }
  }



  if (bottomBarMetals) {
    bottomBarMetals.addEventListener('click', openMetalsModal);
  }

  if (metalsClose) {
    metalsClose.addEventListener('click', closeMetalsModal);
  }

  if (metalsOverlay) {
    metalsOverlay.addEventListener('click', function (e) {
      if (e.target === metalsOverlay) closeMetalsModal();
    });
  }

  if (metalsRefreshBtn) {
    metalsRefreshBtn.addEventListener('click', refreshMetalPrices);
  }


  // --- Initial render ---
  renderCart();
});

// Ripple keyframes
var rippleStyle = document.createElement('style');
rippleStyle.textContent = '@keyframes rippleEffect { to { transform: scale(2.5); opacity: 0; } }';
document.head.appendChild(rippleStyle);

