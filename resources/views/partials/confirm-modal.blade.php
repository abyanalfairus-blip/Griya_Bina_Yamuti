{{--
    Modal konfirmasi pengganti confirm() bawaan browser.
    Pemakaian: tambahkan atribut pada <form>:
        data-confirm="Pesan konfirmasi"
        data-confirm-title="Judul (opsional)"
        data-confirm-ok="Teks tombol (opsional)"
--}}
<div id="confirm-modal" class="fixed inset-0 z-[90] hidden items-end justify-center p-4 sm:items-center" role="alertdialog" aria-modal="true" aria-labelledby="confirm-title" aria-describedby="confirm-text">
    <div data-confirm-backdrop class="absolute inset-0 bg-brand-950/60 opacity-0 backdrop-blur-sm transition-opacity duration-200"></div>
    <div data-confirm-panel class="relative w-full max-w-md translate-y-4 scale-95 overflow-hidden rounded-3xl bg-white opacity-0 shadow-2xl shadow-brand-950/30 transition duration-200">
        <div class="h-1.5 bg-gradient-to-r from-brand-700 via-brand-500 to-leaf-400"></div>
        <div class="p-6 text-center sm:p-7">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-red-50 text-red-600 ring-8 ring-red-50/60">
                <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            </span>
            <h2 id="confirm-title" class="mt-4 font-display text-xl text-brand-900">Hapus data?</h2>
            <p id="confirm-text" class="mt-2 text-sm leading-relaxed text-brand-700">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row">
                <button type="button" data-confirm-cancel class="flex-1 rounded-full border border-brand-200 px-5 py-3 text-sm font-semibold text-brand-800 transition hover:bg-brand-50">Batal</button>
                <button type="button" data-confirm-ok class="flex-1 rounded-full bg-red-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-red-600/25 transition hover:bg-red-700">Ya, hapus</button>
            </div>
        </div>
    </div>
</div>
<script>
(() => {
    const modal = document.getElementById('confirm-modal');
    if (!modal || modal.dataset.ready) return;
    modal.dataset.ready = '1';
    const panel = modal.querySelector('[data-confirm-panel]');
    const backdrop = modal.querySelector('[data-confirm-backdrop]');
    const okBtn = modal.querySelector('[data-confirm-ok]');
    const cancelBtn = modal.querySelector('[data-confirm-cancel]');
    let pending = null, lastFocus = null;

    const open = (form) => {
        pending = form; lastFocus = document.activeElement;
        modal.querySelector('#confirm-title').textContent = form.dataset.confirmTitle || 'Hapus data?';
        modal.querySelector('#confirm-text').textContent = form.dataset.confirm || 'Tindakan ini tidak dapat dibatalkan.';
        okBtn.textContent = form.dataset.confirmOk || 'Ya, hapus';
        modal.classList.remove('hidden'); modal.classList.add('flex');
        document.documentElement.style.overflow = 'hidden';
        requestAnimationFrame(() => {
            backdrop.classList.replace('opacity-0', 'opacity-100');
            panel.classList.remove('translate-y-4', 'scale-95', 'opacity-0');
            cancelBtn.focus();
        });
    };
    const close = () => {
        backdrop.classList.replace('opacity-100', 'opacity-0');
        panel.classList.add('translate-y-4', 'scale-95', 'opacity-0');
        document.documentElement.style.overflow = '';
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
        pending = null;
        if (lastFocus) lastFocus.focus();
    };

    document.addEventListener('submit', (e) => {
        const form = e.target.closest('form[data-confirm]');
        if (!form || form.dataset.confirmed === '1') return;
        e.preventDefault();
        open(form);
    }, true);
    okBtn.addEventListener('click', () => {
        if (!pending) return;
        const form = pending;
        form.dataset.confirmed = '1';
        okBtn.disabled = true; okBtn.textContent = 'Memproses…';
        form.submit();
    });
    cancelBtn.addEventListener('click', close);
    backdrop.addEventListener('click', close);
    document.addEventListener('keydown', (e) => {
        if (modal.classList.contains('hidden')) return;
        if (e.key === 'Escape') close();
        if (e.key === 'Tab') {
            const f = [cancelBtn, okBtn];
            const i = f.indexOf(document.activeElement);
            e.preventDefault();
            f[(i + (e.shiftKey ? -1 : 1) + f.length) % f.length].focus();
        }
    });
})();
</script>
