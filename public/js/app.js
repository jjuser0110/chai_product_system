// =============================================
// SHOPNEST APP.JS
// =============================================

// ---- SIDEBAR ----
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const sidebarClose = document.getElementById('sidebarClose');
const sidebarOverlay = document.getElementById('sidebarOverlay');

function openSidebar() {
  sidebar.classList.add('open');
  sidebarOverlay.classList.add('active');
  document.body.style.overflow = 'hidden';
}
function closeSidebar() {
  sidebar.classList.remove('open');
  sidebarOverlay.classList.remove('active');
  document.body.style.overflow = '';
}
sidebarToggle && sidebarToggle.addEventListener('click', openSidebar);
sidebarClose && sidebarClose.addEventListener('click', closeSidebar);
sidebarOverlay && sidebarOverlay.addEventListener('click', closeSidebar);

// ---- PRODUCT CARD HTML ----
function productCardHTML(product, backHref, highlight) {
  const back = backHref || 'index.html';
  const hasImg = product.images && product.images.length > 0;
  const cardImgInner = hasImg
    ? `<img src="${product.images[0]}" alt="${product.name}" class="product-card-photo" loading="lazy"/>
       ${product.images.length > 1 ? `<span class="product-img-count"><i class="bi bi-images"></i> ${product.images.length}</span>` : ''}`
    : `<span class="product-emoji">${product.emoji}</span>`;

  return `
    <a href="product.html?id=${product.id}&back=${encodeURIComponent(back)}" class="product-card${highlight ? ' product-card--highlight' : ''}">
      <div class="product-card-img">
        ${cardImgInner}
        ${product.badge ? `<span class="product-badge">${product.badge}</span>` : ''}
      </div>
      <div class="product-card-body">
        <h5>${product.name}</h5>
        <p>${product.shortDesc}</p>
        <div class="product-card-footer">
          <span class="product-price">${product.price}</span>
          <span class="product-view-btn">View <i class="bi bi-arrow-right"></i></span>
        </div>
      </div>
    </a>
  `;
}

// ---- CATEGORY CARD HTML ----
function categoryCardHTML(cat) {
  return `
    <a href="category.html?cat=${cat.id}" class="category-card">
      <div class="category-card-icon">
        <i class="bi ${cat.icon}"></i>
      </div>
      <div class="category-card-body">
        <h5>${cat.name}</h5>
        <p>${cat.description}</p>
        <span class="cat-count">${cat.productCount} Products</span>
      </div>
      <i class="bi bi-chevron-right category-arrow"></i>
    </a>
  `;
}

// ---- SEARCH HELPER ----
function setupSearch({ inputId, clearId, metaId, emptyId, emptyTermId, onSearch, onClear }) {
  const input  = document.getElementById(inputId);
  const clear  = document.getElementById(clearId);
  const meta   = document.getElementById(metaId);
  const empty  = document.getElementById(emptyId);
  const emptyT = document.getElementById(emptyTermId);
  if (!input) return;

  let debounceTimer;

  function doSearch(val) {
    const term = val.trim();
    if (clear) clear.classList.toggle('visible', term.length > 0);
    onSearch(term, { meta, empty, emptyT });
  }

  input.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => doSearch(input.value), 180);
  });

  clear && clear.addEventListener('click', () => {
    input.value = '';
    input.focus();
    doSearch('');
    onClear && onClear();
  });
}

// ---- HOME PAGE ----
function initHome() {
  const track = document.getElementById('highlightTrack');
  if (!track) return;

  // Laravel renders highlights from the database.
  // Only use the old static renderer when data.js is available.
  if (typeof getHighlightProducts !== 'function') return;

  const highlights = getHighlightProducts();
  const all = [...highlights, ...highlights];
  track.innerHTML = all.map(p => {
    const hasImg = p.images && p.images.length > 0;
    const imgInner = hasImg
      ? `<img src="${p.images[0]}" alt="${p.name}" class="highlight-card-photo" loading="lazy"/>`
      : `<span class="highlight-emoji">${p.emoji}</span>`;
    return `
      <a href="product.html?id=${p.id}&back=index.html" class="highlight-card">
        <div class="highlight-card-img">
          ${imgInner}
          ${p.badge ? `<span class="product-badge">${p.badge}</span>` : ''}
        </div>
        <div class="highlight-card-body">
          <h6>${p.name}</h6>
          <span class="product-price">${p.price}</span>
        </div>
      </a>
    `;
  }).join('');
}

// ---- CATEGORY PAGE ----
function initCategory() {
  const categoryGrid  = document.getElementById('categoryGrid');
  const productGrid   = document.getElementById('productGrid');
  const searchResults = document.getElementById('searchResults');
  if (!categoryGrid) return;

  const params  = new URLSearchParams(window.location.search);
  const catId   = params.get('cat');
  const pageTitle = document.getElementById('pageHeroTitle');

  // Which pool are we searching?
  // - No catId → search categories + all products
  // - catId    → search only products in that category
  let searchPool = [];   // products
  let isCatListView = false;

  if (catId) {
    const cat      = getCategoryById(catId);
    const products = getProductsByCategory(catId);
    searchPool     = products;

    if (pageTitle) pageTitle.textContent = cat ? cat.name : 'Products';

    const bcList = document.getElementById('breadcrumb');
    if (bcList) {
      bcList.innerHTML = `
        <li><a href="index.html">Home</a></li>
        <li><a href="category.html">Categories</a></li>
        <li>${cat ? cat.name : 'Products'}</li>
      `;
    }

    categoryGrid.style.display = 'none';
    productGrid.style.display  = 'grid';
    productGrid.innerHTML = products.map(p => productCardHTML(p, `category.html?cat=${catId}`)).join('');

  } else {
    isCatListView = true;
    searchPool    = PRODUCTS; // all products for cross-category search
    const breadcrumbCat = document.getElementById('breadcrumb-cat');
    if (breadcrumbCat) breadcrumbCat.remove();
    categoryGrid.style.display = 'grid';
    productGrid.style.display  = 'none';
    categoryGrid.innerHTML = CATEGORIES.map(c => categoryCardHTML(c)).join('');
  }

  // ---- Wire up search ----
  setupSearch({
    inputId:     'searchInput',
    clearId:     'searchClear',
    metaId:      'searchMeta',
    emptyId:     'searchEmpty',
    emptyTermId: 'searchEmptyTerm',

    onSearch(term, { meta, empty, emptyT }) {
      if (!term) {
        // Restore original view
        searchResults.style.display = 'none';
        searchResults.innerHTML     = '';
        empty.style.display         = 'none';
        if (isCatListView) {
          categoryGrid.style.display = 'grid';
          productGrid.style.display  = 'none';
        } else {
          categoryGrid.style.display = 'none';
          productGrid.style.display  = 'grid';
        }
        if (meta) meta.textContent = '';
        return;
      }

      // Hide original grids, show results
      categoryGrid.style.display = 'none';
      productGrid.style.display  = 'none';

      const q = term.toLowerCase();

      // If on the category list view, also search category names
      let catMatches = [];
      if (isCatListView) {
        catMatches = CATEGORIES.filter(c =>
          c.name.toLowerCase().includes(q) ||
          c.description.toLowerCase().includes(q)
        );
      }

      // Search products
      const prodMatches = searchPool.filter(p => {
        const cat = getCategoryById(p.categoryId);
        return (
          p.name.toLowerCase().includes(q)        ||
          p.shortDesc.toLowerCase().includes(q)   ||
          p.description.toLowerCase().includes(q) ||
          (cat && cat.name.toLowerCase().includes(q))
        );
      });

      const totalMatches = catMatches.length + prodMatches.length;

      if (totalMatches === 0) {
        searchResults.style.display = 'none';
        searchResults.innerHTML     = '';
        empty.style.display         = 'flex';
        if (emptyT) emptyT.textContent = `"${term}"`;
        if (meta) meta.textContent = '';
      } else {
        empty.style.display = 'none';
        if (meta) {
          meta.textContent = `${totalMatches} result${totalMatches !== 1 ? 's' : ''} for "${term}"`;
        }

        // Render category cards first, then product cards
        const catHTML  = catMatches.map(c => categoryCardHTML(c)).join('');
        const prodHTML = prodMatches.map(p => productCardHTML(p, window.location.href)).join('');

        // Wrap categories in their own grid section if mixed
        let html = '';
        if (catMatches.length && prodMatches.length) {
          html += `<div class="search-section-label">Categories</div>
                   <div class="category-grid search-section-grid">${catHTML}</div>
                   <div class="search-section-label">Products</div>`;
          searchResults.className = 'product-grid';
          searchResults.innerHTML = html + prodHTML;
        } else if (catMatches.length) {
          searchResults.className = 'category-grid';
          searchResults.innerHTML = catHTML;
        } else {
          searchResults.className = 'product-grid';
          searchResults.innerHTML = prodHTML;
        }

        searchResults.style.display = catMatches.length && !prodMatches.length ? 'grid' : 'grid';
      }
    },

    onClear() {
      // handled inside onSearch('')
    }
  });
}

// ---- HIGHLIGHT PAGE ----
function initHighlight() {
  const grid = document.getElementById('highlightGrid');
  if (!grid) return;

  const highlights = getHighlightProducts();
  grid.innerHTML = highlights.map(p => productCardHTML(p, 'highlight.html')).join('');

  setupSearch({
    inputId:     'searchInput',
    clearId:     'searchClear',
    metaId:      'searchMeta',
    emptyId:     'searchEmpty',
    emptyTermId: 'searchEmptyTerm',

    onSearch(term, { meta, empty, emptyT }) {
      if (!term) {
        grid.style.display  = 'grid';
        empty.style.display = 'none';
        grid.innerHTML = highlights.map(p => productCardHTML(p, 'highlight.html')).join('');
        if (meta) meta.textContent = '';
        return;
      }

      const q = term.toLowerCase();
      const matches = highlights.filter(p => {
        const cat = getCategoryById(p.categoryId);
        return (
          p.name.toLowerCase().includes(q)        ||
          p.shortDesc.toLowerCase().includes(q)   ||
          p.description.toLowerCase().includes(q) ||
          (cat && cat.name.toLowerCase().includes(q))
        );
      });

      if (matches.length === 0) {
        grid.style.display  = 'none';
        empty.style.display = 'flex';
        if (emptyT) emptyT.textContent = `"${term}"`;
        if (meta) meta.textContent = '';
      } else {
        grid.style.display  = 'grid';
        empty.style.display = 'none';
        grid.innerHTML = matches.map(p => productCardHTML(p, 'highlight.html')).join('');
        if (meta) meta.textContent = `${matches.length} result${matches.length !== 1 ? 's' : ''} for "${term}"`;
      }
    }
  });
}

// ---- PRODUCT DETAIL PAGE ----
function initProductDetail() {
  const wrap = document.getElementById('productDetail');
  if (!wrap) return;

  const params  = new URLSearchParams(window.location.search);
  const id      = params.get('id');
  const back    = params.get('back') || 'index.html';
  const product = getProductById(id);

  if (!product) {
    wrap.innerHTML = '<p class="text-center py-5">Product not found.</p>';
    return;
  }

  document.title = `ShopNest – ${product.name}`;
  const cat = getCategoryById(product.categoryId);

  const bc = document.getElementById('detailBreadcrumb');
  if (bc) {
    bc.innerHTML = `
      <li><a href="index.html">Home</a></li>
      ${cat ? `<li><a href="category.html?cat=${cat.id}">${cat.name}</a></li>` : ''}
      <li>${product.name}</li>
    `;
  }

  // Build image slider — use images[] if provided, else fallback to emoji
  const images = product.images && product.images.length ? product.images : null;

  const sliderHTML = images ? `
    <div class="img-slider" id="imgSlider">
      <div class="img-slider-track" id="imgTrack">
        ${images.map((src, i) => `
          <div class="img-slide">
            <img src="${src}" alt="${product.name} photo ${i + 1}" loading="${i === 0 ? 'eager' : 'lazy'}"/>
          </div>
        `).join('')}
      </div>
      ${images.length > 1 ? `
        <button class="img-slider-btn img-slider-prev" id="sliderPrev" aria-label="Previous">
          <i class="bi bi-chevron-left"></i>
        </button>
        <button class="img-slider-btn img-slider-next" id="sliderNext" aria-label="Next">
          <i class="bi bi-chevron-right"></i>
        </button>
        <div class="img-slider-dots" id="sliderDots">
          ${images.map((_, i) => `<button class="img-dot${i === 0 ? ' active' : ''}" data-index="${i}" aria-label="Photo ${i + 1}"></button>`).join('')}
        </div>
        <div class="img-slider-counter" id="sliderCounter">1 / ${images.length}</div>
      ` : ''}
    </div>
    ${images.length > 1 ? `
      <div class="img-thumbnails" id="imgThumbs">
        ${images.map((src, i) => `
          <button class="img-thumb${i === 0 ? ' active' : ''}" data-index="${i}" aria-label="Photo ${i + 1}">
            <img src="${src}" alt="Thumb ${i + 1}" loading="lazy"/>
          </button>
        `).join('')}
      </div>
    ` : ''}
  ` : `
    <div class="product-detail-img">
      <span class="product-detail-emoji">${product.emoji}</span>
      ${product.badge ? `<span class="product-badge product-badge--lg">${product.badge}</span>` : ''}
    </div>
  `;

  wrap.innerHTML = `
    <div class="row g-4 align-items-start">
      <div class="col-12 col-md-5">
        <a href="${back}" class="back-btn"><i class="bi bi-arrow-left"></i> Back</a>
        ${sliderHTML}
      </div>
      <div class="col-12 col-md-7">
        <div class="product-detail-info">
          <span class="product-detail-cat">
            <i class="bi ${cat ? cat.icon : 'bi-tag'}"></i> ${cat ? cat.name : ''}
          </span>
          ${product.badge ? `<span class="product-badge product-badge--lg" style="position:relative;top:auto;left:auto;display:inline-block;margin-bottom:10px;">${product.badge}</span>` : ''}
          <h1 class="product-detail-name">${product.name}</h1>
          <p class="product-detail-short">${product.shortDesc}</p>
          <div class="product-detail-price">${product.price}</div>
          <p class="product-detail-desc">${product.description}</p>
          <div class="product-specs">
            <h6>Key Features</h6>
            <ul>
              ${product.specs.map(s => `<li><i class="bi bi-check-circle-fill"></i> ${s}</li>`).join('')}
            </ul>
          </div>
          <div class="product-detail-actions">
            <a href="https://wa.me/60123456789?text=Hi!%20I'm%20interested%20in%20${encodeURIComponent(product.name)}" target="_blank" class="btn-buy btn-whatsapp">
              <i class="bi bi-whatsapp"></i> Order via WhatsApp
            </a>
            <a href="contact.html" class="btn-buy btn-contact">
              <i class="bi bi-chat-dots-fill"></i> Contact Us
            </a>
          </div>
        </div>
      </div>
    </div>
  `;

  // ---- Init slider logic ----
  if (images && images.length > 1) {
    let current = 0;
    const track  = document.getElementById('imgTrack');
    const dots   = document.querySelectorAll('.img-dot');
    const thumbs = document.querySelectorAll('.img-thumb');
    const counter = document.getElementById('sliderCounter');

    function goTo(index) {
      current = (index + images.length) % images.length;
      track.style.transform = `translateX(-${current * 100}%)`;
      dots.forEach((d, i) => d.classList.toggle('active', i === current));
      thumbs.forEach((t, i) => t.classList.toggle('active', i === current));
      if (counter) counter.textContent = `${current + 1} / ${images.length}`;
    }

    document.getElementById('sliderPrev').addEventListener('click', () => goTo(current - 1));
    document.getElementById('sliderNext').addEventListener('click', () => goTo(current + 1));
    dots.forEach(d => d.addEventListener('click', () => goTo(+d.dataset.index)));
    thumbs.forEach(t => t.addEventListener('click', () => goTo(+t.dataset.index)));

    // Touch / swipe support
    const slider = document.getElementById('imgSlider');
    let touchStartX = 0;
    slider.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
    slider.addEventListener('touchend', e => {
      const diff = touchStartX - e.changedTouches[0].clientX;
      if (Math.abs(diff) > 40) goTo(diff > 0 ? current + 1 : current - 1);
    });

    // Keyboard arrow support
    document.addEventListener('keydown', e => {
      if (e.key === 'ArrowLeft')  goTo(current - 1);
      if (e.key === 'ArrowRight') goTo(current + 1);
    });
  }
}


// =============================================
// LANGUAGE SWITCHER — Laravel frontend
// =============================================

function applyLang() {
  if (typeof t !== 'function') return;

  // Sidebar / bottom navigation
  const bottomLabels = document.querySelectorAll('.bottom-nav-item span');
  const navKeys = ['nav_home', 'nav_category', 'nav_highlight', 'nav_contact'];

  bottomLabels.forEach((el, i) => {
    if (navKeys[i]) el.textContent = t(navKeys[i]);
  });

  const sidebarLinks = document.querySelectorAll('.sidebar-nav li a');
  sidebarLinks.forEach((el, i) => {
    if (!navKeys[i]) return;

    const icon = el.querySelector('i');
    const iconHTML = icon ? icon.outerHTML : '';
    el.innerHTML = iconHTML + ' ' + t(navKeys[i]);
  });

  const footer = document.querySelector('.sidebar-footer p');
  if (footer) footer.textContent = t('sidebar_footer');

  // Language button active state
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.classList.toggle(
      'lang-btn--active',
      btn.dataset.lang === currentLang
    );
  });
}

function renderHomeText() {
  if (typeof t !== 'function') return;

  const setText = (id, key) => {
    const el = document.getElementById(id);
    if (el) el.textContent = t(key);
  };

  // About
  setText('aboutTag', 'about_tag');
  setText('aboutAcc', 'about_title_acc');
  setText('aboutP1', 'about_p1');
  setText('aboutP2', 'about_p2');

  const aboutTitle = document.getElementById('aboutTitle');
  if (aboutTitle) {
    const textNode = [...aboutTitle.childNodes].find(
      node => node.nodeType === Node.TEXT_NODE && node.textContent.trim()
    );

    if (textNode) {
      textNode.textContent = ' ' + t('about_title') + ' ';
    }
  }

  // About icon cards — works even without individual IDs
  const iconLabels = document.querySelectorAll('.about-icon-card span');
  const iconKeys = [
    'about_icon_1',
    'about_icon_2',
    'about_icon_3',
    'about_icon_4'
  ];

  iconLabels.forEach((el, i) => {
    if (iconKeys[i]) el.textContent = t(iconKeys[i]);
  });

  // Stats
  setText('statProducts', 'about_stat_products');
  setText('statCustomers', 'about_stat_customers');
  setText('statRating', 'about_stat_rating');

  // Highlight
  setText('hlTag', 'highlight_tag');
  setText('hlAcc', 'highlight_acc');
  setText('viewAll', 'view_all');

  const hlTitle = document.getElementById('hlTitle');
  if (hlTitle) {
    const textNode = [...hlTitle.childNodes].find(
      node => node.nodeType === Node.TEXT_NODE && node.textContent.trim()
    );

    if (textNode) {
      textNode.textContent = ' ' + t('highlight_title') + ' ';
    }
  }

  // Contact strip
  setText('csAcc', 'contact_strip_acc');
  setText('csSub', 'contact_strip_sub');
  setText('csBtn', 'contact_strip_btn');

  const csTitle = document.getElementById('csTitle');
  if (csTitle) {
    const textNode = [...csTitle.childNodes].find(
      node => node.nodeType === Node.TEXT_NODE && node.textContent.trim()
    );

    if (textNode) {
      textNode.textContent = ' ' + t('contact_strip_title') + ' ';
    }
  }
}

function initLangSwitcher() {
  document.querySelectorAll('.lang-btn').forEach(btn => {
    // Avoid attaching duplicate handlers if this function is called again.
    if (btn.dataset.langBound === '1') return;

    btn.dataset.langBound = '1';

    btn.addEventListener('click', function (e) {
      e.preventDefault();

      const code = this.dataset.lang;
      if (!code || typeof setLang !== 'function') return;

      setLang(code);

      applyLang();
      renderHomeText();
    });
  });

  applyLang();
}

// =============================================
// PAGE ROUTER
// =============================================

document.addEventListener('DOMContentLoaded', () => {
  // Initialize language first.
  if (typeof setLang === 'function' && typeof currentLang !== 'undefined') {
    setLang(currentLang);
  }

  initLangSwitcher();

  // Laravel Home page
  if (document.getElementById('aboutTag')) {
    renderHomeText();
    return;
  }

  // Original static ShopNest pages
  const page = window.location.pathname.split('/').pop();

  if (page === 'index.html' || page === '') initHome();
  if (page === 'category.html') initCategory();
  if (page === 'highlight.html') initHighlight();
  if (page === 'product.html') initProductDetail();
});
