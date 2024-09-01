//=====================================
//    390px未満はJS で viewport を固定する
//=====================================
(function () {
  const viewportMeta = document.querySelector('meta[name="viewport"]');

  const updateViewport = () => {
    const contentValue = window.outerWidth > 390
      ? "width=device-width,initial-scale=1"
      : "width=390";

    if (viewportMeta.getAttribute("content") !== contentValue) {
      viewportMeta.setAttribute("content", contentValue);
    }
  };

  window.addEventListener("resize", updateViewport);
  updateViewport();
})();

//=====================================
//    ヘッダーがスクロールすると変化
//=====================================
document.addEventListener('DOMContentLoaded', function() {
  var header = document.querySelector('.js-header');
  var iconWrap = document.querySelector('.l-drawer__iconWrap'); // クラスセレクタを使用
  var scrollThreshold = 100; // スクロールの閾値（100px）

  // スクロールイベントのリスナーを設定
  window.addEventListener('scroll', function() {
    if (window.scrollY > scrollThreshold) {
      // スクロール位置が閾値を超えたときにクラスを追加
      header.classList.add('is-scrolled');
      iconWrap.classList.add('is-scrolled'); // アイコンラップにクラスを追加
    } else {
      // スクロール位置が閾値以下のときにクラスを削除
      header.classList.remove('is-scrolled');
      iconWrap.classList.remove('is-scrolled'); // アイコンラップからクラスを削除
    }
  });
});

//=====================================
//    スムーススクロール
//=====================================
const initializeSmoothScroll = () => {
  document.addEventListener("click", handleSmoothScrollClick, { capture: true });
};

const getHeaderBlockSize = () => {
  const header = document.querySelector("[data-fixed-header]");
  return header ? window.getComputedStyle(header).blockSize : "0";
};

const scrollToTarget = (element) => {
  const headerBlockSize = getHeaderBlockSize();
  element.style.scrollMarginBlockStart = `calc(${headerBlockSize} + 100px)`;

  const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const behavior = prefersReducedMotion ? "instant" : "smooth";

  element.scrollIntoView({ behavior, inline: "end" });
  element.style.scrollMarginBlockStart = "";
};

const focusTarget = (element) => {
  element.focus({ preventScroll: true });

  if (document.activeElement !== element) {
    element.setAttribute("tabindex", "-1");
    element.focus({ preventScroll: true });
  }
};

const handleSmoothScrollClick = (event) => {
  if (event.button !== 0) return;

  const anchorLink = event.target.closest('a[href*="#"]');
  if (!anchorLink) return;

  const targetId = anchorLink.hash.slice(1);
  const targetElement = document.getElementById(decodeURIComponent(targetId));

  if (targetElement && anchorLink.getAttribute("data-smooth-scroll") !== "disabled") {
    event.preventDefault();
    scrollToTarget(targetElement);
    focusTarget(targetElement);
    history.pushState({}, "", anchorLink.hash);
  }
};

// 初期化
initializeSmoothScroll();


//=====================================
//    ドロワーアイコン
//=====================================
const CLASS = "-active";
let flg = false;

const scrollingElement = () => {
  return "scrollingElement" in document
    ? document.scrollingElement
    : document.documentElement;
};

const backgroundFix = (bool) => {
  const scrollY = bool
    ? scrollingElement().scrollTop
    : parseInt(document.body.style.top || "0");
  const fixedStyles = {
    height: "100svh",
    position: "fixed",
    top: `${scrollY * -1}px`,
    left: "0",
    width: "100vw",
  };

  Object.keys(fixedStyles).forEach((key) => {
    document.body.style[key] = bool ? fixedStyles[key] : "";
  });

  const drawerBackground = document.getElementById("js-drawer-background");
  drawerBackground.classList.toggle("-active", bool);
  if (!bool) {
    window.scrollTo(0, scrollY * -1);
  }
};

const closeDrawer = () => {
  hamburger.classList.remove(CLASS);
  menu.classList.remove(CLASS);
  iconWrap.classList.remove(CLASS); //ドロワーラップにもactive付与する
  backgroundFix(false);
  hamburger.focus();
  hamburger.setAttribute("aria-expanded", "false");
  flg = false;
};

const hamburger = document.getElementById("js-drawer-icon");
const focusTrap = document.getElementById("js-focus-trap");
const menu = document.querySelector("#js-drawer-content");
const drawerBackground = document.getElementById("js-drawer-background");
const iconWrap = document.querySelector(".l-drawer__iconWrap");//ドロワーラップにもactive付与する

if (hamburger) {
  hamburger.addEventListener("click", () => {
    hamburger.classList.toggle(CLASS);
    menu.classList.toggle(CLASS);
    iconWrap.classList.toggle(CLASS);//ドロワーラップ
    backgroundFix(!flg);
    hamburger.setAttribute("aria-expanded", flg ? "false" : "true");
    flg = !flg;
  });
}

window.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    closeDrawer();
  }
});

focusTrap.addEventListener("focus", () => {
  hamburger.focus();
});

drawerBackground.addEventListener("click", () => {
  closeDrawer();
});

// メニュー内のリンクがクリックされたときにドロワーを閉じる
const menuLinks = document.querySelectorAll('.l-drawer__list a, .l-drawer__contentKeyword a');
menuLinks.forEach(link => {
  link.addEventListener('click', (event) => {
    // ページ内リンクの場合はスムーススクロール
    if (event.currentTarget.getAttribute('href').startsWith('#')) {
      event.preventDefault();
      const targetId = event.currentTarget.getAttribute('href').substring(1);
      const target = document.getElementById(targetId);
      if (target) {
        closeDrawer();
        setTimeout(() => {
          target.scrollIntoView({ behavior: 'smooth' });
        }, 300); // ドロワーが閉じるのを待ってからスクロール
      }
    } else {
      // 外部リンクや別ページへのリンクは通常の遷移
      closeDrawer();
    }
  });
});

//=====================================
//    splide
//=====================================
document.addEventListener('DOMContentLoaded', function() {
  function initSplide(selector, options) {
    try {
      const splideElements = document.querySelectorAll(selector);  // クラス名で複数要素を取得
      splideElements.forEach(splideElement => {
        const splide = new Splide(splideElement, options);

        // プログレスバーとナビゲーション矢印の要素を取得
        const bar = splide.root.querySelector('.my-carousel-progress-bar');
        const progressBar = splide.root.querySelector('.my-carousel-progress');
        const prevArrow = splide.root.querySelector('.splide__arrow--prev');
        const nextArrow = splide.root.querySelector('.splide__arrow--next');

        // プログレスバーが存在する場合の処理
        if (progressBar) {
          // ARIA属性の設定
          progressBar.setAttribute('role', 'slider');
          progressBar.setAttribute('aria-label', 'スライダーの進行状況');
          progressBar.setAttribute('aria-valuemin', '0');
          progressBar.setAttribute('aria-valuemax', '100');
          progressBar.setAttribute('aria-controls', splideElement.id);

          // プログレスバーの更新関数
          function updateProgress() {
            const end = splide.Components.Controller.getEnd() + 1;
            const rate = Math.min((splide.index + 1) / end, 1);
            const percentage = Math.round(rate * 100);
            
            bar.style.width = `${percentage}%`;
            progressBar.setAttribute('aria-valuenow', percentage);

            // 矢印の状態更新
            prevArrow.classList.toggle('splide__arrow--disabled', splide.index === 0);
            nextArrow.classList.toggle('splide__arrow--disabled', splide.index === end - 1);
          }

          // プログレスバーのクリックイベント
          progressBar.addEventListener('click', (e) => {
            const rect = progressBar.getBoundingClientRect();
            const clickRatio = (e.clientX - rect.left) / rect.width;
            const newIndex = Math.floor(clickRatio * (splide.Components.Controller.getEnd() + 1));
            splide.go(newIndex);
          });

          splide.on('mounted move', updateProgress);
        }

        splide.mount();
      });
    } catch (error) {
      console.warn(`Failed to initialize Splide for selector ${selector}:`, error);
    }
  }

  // メインビジュアル用Splideの初期化（トップページのみ）
  if (document.querySelector('#splide-mv')) {
    initSplide('#splide-mv', {
      type: 'loop',
      autoplay: true,
      pauseOnHover: false,
      pauseOnFocus: false,
      speed: 700,
      padding: '36%',
      focus: 'center',
      updateOnMove: true,
      gap: 80,
      breakpoints: {
        1680: { gap: 64, padding: '29%' },
        1280: { gap: 40, padding: '30%' },
        640: { gap: 24, padding: '20%' },
        576: { gap: 16, padding: '10%' }
      }
    });
  }

  // おすすめ商品用Splideの初期化（トップページとシングルページ共通）
  if (document.querySelectorAll('.splide-recommend').length > 0) {  // クラス名で判定
    initSplide('.splide-recommend', {
      mediaQuery: 'min',
      fixedWidth: '302px',
      gap: 24,
      pauseOnHover: false,
      pauseOnFocus: false,
      updateOnMove: true,
      focus: 0,
      omitEnd: true,
      breakpoints: {
        1024: { gap: 32 },
        768: { gap: 24 },
        640: { gap: 24 }
      }
    });
  }
});


//=====================================
//    タブパネル
//=====================================
const initializeTabs = (root, firstView = 1) => {
  if (!root) return;

  const tablist = root.querySelector('[role="tablist"]');
  const tabs = root.querySelectorAll('[role="tab"]');
  const tabpanels = root.querySelectorAll('[role="tabpanel"]');

  if (!tablist || tabs.length === 0 || tabpanels.length === 0) return;

  const currentIndex = Math.max(0, firstView - 1);

  const setTabAttributes = (tabs, tabpanels) => {
    tabs.forEach((tab, index) => {
      tab.setAttribute("aria-selected", "false");
      tab.setAttribute("aria-controls", tabpanels[index].id);
      tab.setAttribute("tabindex", "-1");
    });
  };
    activateTab(tabs, tabpanels, currentIndex);

      // ウィンドウの幅に基づいて aria-orientation を更新する関数
  const updateAriaOrientation = () => {
    if (window.innerWidth >= 750) {
      tablist.setAttribute('aria-orientation', 'horizontal');
    } else {
      tablist.setAttribute('aria-orientation', 'vertical');
    }
  };

  // タブを初期化し、属性を設定する
  setTabAttributes(tabs, tabpanels);
  activateTab(tabs, tabpanels, currentIndex);

  // 読み込み時およびリサイズ時に aria-orientation を更新する
  updateAriaOrientation();
  window.addEventListener('resize', updateAriaOrientation);


  tabs.forEach((tab, index) => {
    tab.addEventListener(
      "click",
      (event) => handleClick(event, tabs, tabpanels, index),
      false
    );
    tab.addEventListener(
      "keyup",
      (event) => handleKeyNavigation(event, tabs, tabpanels, index, tablist),
      false
    );
  });

  tabpanels.forEach((panel) => {
    panel.addEventListener(
      "beforematch",
      (event) => handleBeforeMatch(event, tabs, tabpanels),
      true
    );
  });
};

// Function to set necessary attributes on tabs and tab panels
const setTabAttributes = (tabs, tabpanels) => {
  tabs.forEach((tab, index) => {
    tab.setAttribute("aria-selected", "false");
    tab.setAttribute("aria-controls", tabpanels[index].id);
    tab.setAttribute("tabindex", "-1");
  });
};

// Function to switch the active tab
const activateTab = (tabs, tabpanels, index) => {
  tabs.forEach((tab, i) => {
    const isSelected = i === index;
    tab.setAttribute("aria-selected", String(isSelected));
    tab.setAttribute("tabindex", isSelected ? "0" : "-1");
  });

  tabpanels.forEach((tabpanel, i) => {
    if (i !== index) {
      tabpanel.setAttribute("hidden", "until-found");
      tabpanel.classList.add("hidden-tab"); // CSSクラスを追加
      tabpanel.removeAttribute("tabindex");
    } else {
      tabpanel.removeAttribute("hidden");
      tabpanel.classList.remove("hidden-tab"); // CSSクラスを削除
      tabpanel.setAttribute("tabindex", "0");
    }
  });
};

// Function to handle click events
const handleClick = (event, tabs, tabpanels, index) => {
  event.preventDefault();
  activateTab(tabs, tabpanels, index);
};

// Function to handle keyboard navigation
const handleKeyNavigation = (event, tabs, tabpanels, currentIndex, tablist) => {
  const orientation = tablist.getAttribute("aria-orientation") || "horizontal";

  const keyActions = {
    [orientation === "vertical" ? "ArrowUp" : "ArrowLeft"]: () =>
      currentIndex - 1 >= 0 ? currentIndex - 1 : tabs.length - 1,
    [orientation === "vertical" ? "ArrowDown" : "ArrowRight"]: () =>
      (currentIndex + 1) % tabs.length,
    Home: () => 0,
    End: () => tabs.length - 1
  };

  const action = keyActions[event.key];

  if (action) {
    event.preventDefault();
    const newIndex = action();
    tabs[newIndex].focus();
    activateTab(tabs, tabpanels, newIndex);
  }
};

// Function to handle the beforematch event
const handleBeforeMatch = (event, tabs, tabpanels) => {
  const panel = event.currentTarget;
  const tabIndex = Array.from(tabpanels).indexOf(panel);

  if (tabIndex !== -1) {
    activateTab(tabs, tabpanels, tabIndex);
  }
};

const target = document.getElementById("js-tabs");

initializeTabs(target);


//=====================================
//    Topページの記事表示数を画面幅で変更する
//=====================================
function getArticleCount() {
  const screenWidth = window.innerWidth;
  
  if (screenWidth > 950) {
      return 9; // デスクトップ用の記事数
  } else if (screenWidth > 768) {
      return 6; // タブレット用の記事数
  } else {
      return 3; // モバイル用の記事数
  }
}

// 初期ロード時の設定
let articleCount = getArticleCount();

// 画面リサイズ時にも対応
window.addEventListener('resize', function() {
  articleCount = getArticleCount();
});
