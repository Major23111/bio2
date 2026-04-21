<label class="relative inline-flex items-center cursor-pointer select-none">
    <input type="checkbox" class="sr-only peer" {{ ($checked ?? false) ? 'checked' : '' }}>
    <div class="w-11 h-[22px] bg-slate-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-200 after:border after:rounded-full after:h-[18px] after:w-[18px] after:transition-all peer-checked:bg-primary-600 shadow-inner"></div>
</label>
