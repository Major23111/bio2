<div class="flex justify-end pt-4 lg:pt-0">
    <div class="relative inline-block text-left" id="admin-notifications-dropdown">
        <button type="button" 
            class="relative inline-flex items-center justify-center h-10 w-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition shadow-sm cursor-pointer"
            onclick="document.getElementById('notifications-menu').classList.toggle('hidden')">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            @php
                $unreadCount = auth()->user() ? auth()->user()->unreadNotifications->count() : 0;
            @endphp
            @if($unreadCount > 0)
                <span class="absolute top-1.5 right-2 block h-2 w-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
            @endif
        </button>

        <!-- Dropdown menu -->
        <div id="notifications-menu" class="hidden absolute right-0 z-[100] mt-2 w-80 origin-top-right rounded-2xl bg-white shadow-xl ring-1 ring-slate-900/5 focus:outline-none overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50">
                <h3 class="text-[13px] font-bold text-slate-900">Notifications</h3>
                @if($unreadCount > 0)
                    <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-[10px] font-bold text-rose-700 ring-1 ring-inset ring-rose-600/10">{{ $unreadCount }} new</span>
                @endif
            </div>
            
            <div class="max-h-80 overflow-y-auto">
                @if(auth()->user() && auth()->user()->notifications->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach(auth()->user()->unreadNotifications->take(5) as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}" class="block p-4 hover:bg-slate-50 transition">
                                <p class="text-[13px] font-bold text-slate-900">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                <p class="text-[12px] text-slate-500 mt-0.5 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                                <p class="text-[10px] font-medium text-slate-400 mt-1.5">{{ $notification->created_at->diffForHumans() }}</p>
                            </a>
                        @endforeach
                        
                        @if(auth()->user()->unreadNotifications->isEmpty())
                            <div class="p-4 text-center">
                                <p class="text-[13px] text-slate-500">No new notifications</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-6 text-center flex flex-col items-center justify-center">
                        <svg class="h-8 w-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <p class="text-[13px] font-medium text-slate-500">You're all caught up!</p>
                    </div>
                @endif
            </div>
            
            @if(auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                <div class="border-t border-slate-100 bg-slate-50 px-4 py-2 text-center">
                    <form action="{{ route('admin.notifications.mark-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-[12px] font-semibold text-primary-600 hover:text-primary-700">Mark all as read</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
