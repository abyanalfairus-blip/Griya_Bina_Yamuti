/*
 * Animasi muncul saat scroll + hitung naik angka + muat peta saat mendekat.
 * Konten hanya "dirender" (ditampilkan) ketika masuk layar, seperti wakafsalman.or.id.
 * - Tanpa JavaScript atau dengan "kurangi gerakan" aktif, semua konten tampil biasa.
 * - Tidak perlu menandai HTML satu per satu: skrip memilih sendiri unit yang dianimasikan.
 */
(function () {
    'use strict';

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var canObserve = 'IntersectionObserver' in window;
    var SKIP_TAGS = { TEMPLATE: 1, SCRIPT: 1, STYLE: 1, NOSCRIPT: 1 };

    function visibleKids(el) {
        return Array.prototype.filter.call(el.children, function (c) {
            if (SKIP_TAGS[c.tagName]) return false;
            if (c.hasAttribute('data-no-reveal') || c.classList.contains('sr-only')) return false;
            // elemen dekorasi absolut/fixed (pola bintang, tombol WhatsApp) tidak dianimasikan
            if (c.classList.contains('absolute') || c.classList.contains('fixed')) return false;
            return true;
        });
    }

    function isGrid(el) {
        return el.classList.contains('grid') || el.tagName === 'UL' || el.tagName === 'OL' || el.tagName === 'DL';
    }

    // Kontainer interaktif (Alpine / formulir) dianimasikan utuh, tidak dipecah per bagian.
    function isAtomic(el) {
        return el.hasAttribute('x-data') || el.tagName === 'FORM' || el.hasAttribute('data-reveal-whole');
    }

    var units = [];

    function mark(el, variant) {
        if (el.hasAttribute('data-reveal')) return;
        el.setAttribute('data-reveal', variant || 'up');
        units.push(el);
    }

    function expand(container, depth) {
        if (isAtomic(container)) { mark(container, 'up'); return; }
        var kids = visibleKids(container);
        if (!kids.length) return;
        if (kids.length === 1 && depth < 3) { expand(kids[0], depth + 1); return; }

        kids.forEach(function (kid) {
            var inner = visibleKids(kid);
            if (isGrid(kid) && !isAtomic(kid) && inner.length > 1) {
                inner.forEach(function (c, i) {
                    var v = 'up';
                    if (inner.length === 2) v = i === 0 ? 'left' : 'right';
                    mark(c, v);
                });
            } else {
                mark(kid, 'up');
            }
        });
    }

    function collect() {
        var roots = document.querySelectorAll('main > section, main > div, footer > div:first-of-type');
        Array.prototype.forEach.call(roots, function (r) {
            if (r.hasAttribute('data-no-reveal')) return;
            expand(r, 0);
        });
    }

    function done(el) {
        el.classList.add('is-in');
        // Lepas atribut setelah transisi selesai agar efek hover (angkat kartu, dsb.) kembali normal.
        window.setTimeout(function () { el.removeAttribute('data-reveal'); el.classList.remove('is-in'); el.style.removeProperty('--d'); }, 1300);
    }

    /* ---------- hitung naik angka ---------- */
    function format(n) { return Math.round(n).toLocaleString('id-ID'); }

    function countUp(el) {
        var target = parseFloat(el.getAttribute('data-count'));
        if (isNaN(target)) return;
        var prefix = el.getAttribute('data-prefix') || '';
        if (reduce || target === 0) { el.textContent = prefix + format(target); return; }
        var dur = 1500, start = null;
        function tick(ts) {
            if (start === null) start = ts;
            var p = Math.min((ts - start) / dur, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.textContent = prefix + format(target * eased);
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    /* ---------- muat iframe (peta) hanya saat mendekati layar ---------- */
    function lazyFrames() {
        var frames = document.querySelectorAll('iframe[data-lazy-src]');
        if (!frames.length) return;
        function load(f) { f.src = f.getAttribute('data-lazy-src'); f.removeAttribute('data-lazy-src'); }
        if (!canObserve) { Array.prototype.forEach.call(frames, load); return; }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { load(e.target); io.unobserve(e.target); }
            });
        }, { rootMargin: '400px 0px' });
        Array.prototype.forEach.call(frames, function (f) { io.observe(f); });
    }

    function init() {
        lazyFrames();

        var counters = Array.prototype.slice.call(document.querySelectorAll('[data-count]'));
        var counterIo = null;
        if (canObserve && !reduce) {
            counters.forEach(function (c) { c.textContent = (c.getAttribute('data-prefix') || '') + '0'; });
            counterIo = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) { countUp(e.target); counterIo.unobserve(e.target); }
                });
            }, { threshold: 0.4 });
            counters.forEach(function (c) { counterIo.observe(c); });
        }

        if (reduce || !canObserve) return;

        collect();
        if (!units.length) return;

        var io = new IntersectionObserver(function (entries) {
            var visible = entries.filter(function (e) { return e.isIntersecting; });
            visible.sort(function (a, b) {
                return (a.target.compareDocumentPosition(b.target) & 4) ? -1 : 1; // urut sesuai posisi di halaman
            });
            visible.forEach(function (e, i) {
                e.target.style.setProperty('--d', Math.min(i, 5) * 90 + 'ms');
                done(e.target);
                io.unobserve(e.target);
            });
        }, { threshold: 0, rootMargin: '0px 0px -8% 0px' });

        units.forEach(function (u) { io.observe(u); });

        // Jaring pengaman: jika karena alasan apa pun ada yang belum tampil setelah 6 detik di layar, tampilkan.
        window.setTimeout(function () {
            units.forEach(function (u) {
                if (u.hasAttribute('data-reveal') && !u.classList.contains('is-in')) {
                    var r = u.getBoundingClientRect();
                    if (r.top < window.innerHeight && r.bottom > 0) done(u);
                }
            });
        }, 6000);
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
