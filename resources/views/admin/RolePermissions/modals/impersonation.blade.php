<div id="impersonationModal" class="fixed inset-0 z-[9999] hidden" data-role-modal-root aria-hidden="true">
    <div class="absolute inset-0 bg-[#07162f]/55 opacity-0 backdrop-blur-sm transition-opacity duration-300" data-modal-backdrop></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div class="pointer-events-auto relative w-full max-w-lg translate-y-4 scale-95 opacity-0 overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_32px_96px_rgba(15,23,42,0.18)] transition-all duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] max-h-[92vh] overflow-y-auto role-modal-scroll" data-modal-dialog>
            <div class="flex items-start justify-between border-b border-slate-100 px-8 pb-6 pt-8">
                <div>
                    <h3 class="text-[19px] font-bold text-slate-900 tracking-tight leading-none mb-1.5 font-display">New Impersonation Session</h3>
                    <p class="text-[10px] text-slate-400 tracking-widest font-black uppercase">AUDIT &amp; COMPLIANCE TOOLKIT</p>
                </div>
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" data-role-modal-close>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.role-permission.impersonations.store') }}" class="p-8 space-y-5" id="impersonation-form">
                @csrf

                {{-- USER SELECTION (REQUIRED) — typeahead with all users shown on click --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">USER SELECTION (REQUIRED)</label>
                    <div class="relative" id="user-typeahead-wrapper">
                        <input
                            type="text"
                            id="impersonation-user-input"
                            data-role-modal-autofocus
                            placeholder="Click to see all users or type to filter..."
                            autocomplete="off"
                            class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-[13px] font-bold text-slate-800 outline-none focus:border-primary-600 transition"
                        >
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>

                        {{-- Dropdown list that appears below the input --}}
                        <div
                            id="user-typeahead-dropdown"
                            class="absolute z-50 left-0 right-0 top-full mt-1 max-h-48 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-lg hidden"
                        ></div>
                    </div>

                    {{-- Hidden field that stores the selected user id for form submission --}}
                    <input type="hidden" name="impersonated_user_id" id="impersonated-user-id-field" value="" required>
                </div>

                {{-- Impersonator Name (Required) --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">IMPERSONATOR NAME (REQUIRED)</label>
                    <div class="relative">
                        <input type="text" name="impersonator_name" value="Admin Root" placeholder="Enter your full name for auditing..." class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-[13px] font-bold text-slate-800 outline-none focus:border-primary-600 transition" required>
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                        </svg>
                    </div>
                </div>

                {{-- Session Expiration --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 tracking-widest uppercase mb-2">SESSION EXPIRATION</label>
                    <input type="datetime-local" name="ended_at" class="h-11 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 text-[13px] font-bold text-slate-800 outline-none focus:border-primary-600 transition" required>
                </div>

                {{-- Info box --}}
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-primary-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <p class="text-[11px] font-medium text-slate-500 leading-relaxed italic">
                            The session will automatically expire and revoke all granted tokens after the selected date and time. All activities are logged under your root auditor ID.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-50">
                    <button type="button" class="h-11 px-6 text-[13px] font-bold text-slate-500 hover:text-slate-900 transition" data-role-modal-close>Discard</button>
                    <button type="submit" class="h-11 px-8 rounded-xl bg-primary-600 text-white text-[13px] font-bold shadow-lg shadow-primary-600/20 hover:bg-primary-700 transition active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                        </svg>
                        Start Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    // All elements needed for the typeahead.
    var searchInput   = document.getElementById('impersonation-user-input');
    var dropdown      = document.getElementById('user-typeahead-dropdown');
    var hiddenIdField = document.getElementById('impersonated-user-id-field');

    // The URL for the user search endpoint.
    var searchUrl = '{{ route('admin.role-permission.users.search') }}';

    // Holds the pending fetch timer so rapid typing doesn't fire too many requests.
    var debounceTimer = null;

    // Render a list of user objects into the dropdown.
    function renderDropdown(users) {
        dropdown.innerHTML = '';

        if (users.length === 0) {
            dropdown.innerHTML = '<div class="px-4 py-3 text-[12px] text-slate-400 font-medium">No users found.</div>';
            dropdown.classList.remove('hidden');
            return;
        }

        // Build one row per user.
        users.forEach(function (user) {
            var row = document.createElement('div');
            row.className = 'px-4 py-2.5 cursor-pointer hover:bg-slate-50 transition flex items-center gap-3';
            row.innerHTML =
                '<div class="h-7 w-7 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center text-[10px] font-black shrink-0">'
                + user.name.charAt(0).toUpperCase()
                + '</div>'
                + '<div>'
                + '<div class="text-[12px] font-bold text-slate-800">' + user.name + '</div>'
                + '<div class="text-[10px] text-slate-400 font-medium">' + user.email + '</div>'
                + '</div>';

            // When a user is clicked, fill the input and store the id.
            row.addEventListener('mousedown', function (event) {
                // Prevent the input blur from hiding dropdown before click registers.
                event.preventDefault();

                searchInput.value = user.name + ' (' + user.email + ')';
                hiddenIdField.value = user.id;
                dropdown.classList.add('hidden');
            });

            dropdown.appendChild(row);
        });

        dropdown.classList.remove('hidden');
    }

    // Fetch users from the backend and render them.
    function fetchUsers(searchTerm) {
        var url = searchUrl + '?q=' + encodeURIComponent(searchTerm);

        fetch(url)
            .then(function (response) { return response.json(); })
            .then(function (users) { renderDropdown(users); })
            .catch(function () { dropdown.classList.add('hidden'); });
    }

    // Show all users immediately when the input is focused.
    searchInput.addEventListener('focus', function () {
        // Clear any previous selection when the user reopens the dropdown.
        hiddenIdField.value = '';
        fetchUsers('');
    });

    // Filter users as the user types.
    searchInput.addEventListener('input', function () {
        var typedText = searchInput.value.trim();

        // Clear hidden id when user changes text after a selection.
        hiddenIdField.value = '';

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            fetchUsers(typedText);
        }, 250);
    });

    // Hide the dropdown when the input loses focus.
    searchInput.addEventListener('blur', function () {
        setTimeout(function () {
            dropdown.classList.add('hidden');
        }, 150);
    });

    // Validate that a user was actually selected before the form submits.
    document.getElementById('impersonation-form').addEventListener('submit', function (event) {
        if (! hiddenIdField.value) {
            event.preventDefault();
            searchInput.classList.add('border-red-400');
            searchInput.placeholder = 'Please select a user from the list.';
        } else {
            searchInput.classList.remove('border-red-400');
        }
    });
})();
</script>
