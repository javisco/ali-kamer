{{-- Composant cloche de notifications.
     Usage : @include('components.notification-bell') dans ton layout base
     (à côté du menu utilisateur dans la navbar, par exemple). --}}

<div class="relative" x-data="{ open: false }" @click.outside="open = false">

    <button @click="open = !open" class="relative p-2 rounded-full hover:bg-gray-100 transition">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <span id="notifBadge"
            class="hidden absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">
            0
        </span>
    </button>

    <div x-show="open" x-transition
        class="absolute right-0 mt-2 w-80 max-w-[90vw] bg-white rounded-xl shadow-lg border border-gray-100 z-50"
        style="display: none;">

        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm text-gray-800">Notifications</h3>
            <button id="notifMarkAllRead" class="text-xs text-blue-600 hover:underline">
                Tout marquer comme lu
            </button>
        </div>

        <div id="notifList" class="max-h-96 overflow-y-auto divide-y divide-gray-50">
            <div class="p-4 text-center text-xs text-gray-400">Chargement...</div>
        </div>

    </div>
</div>

@once
    @push('scripts')
        <script>
            (function() {
                const badge = document.getElementById('notifBadge');
                const list = document.getElementById('notifList');
                const markAllBtn = document.getElementById('notifMarkAllRead');

                const urls = {
                    index: "{{ route('notifications.index') }}",
                    unreadCount: "{{ route('notifications.unread-count') }}",
                    read: (id) => `/notifications/${id}/read`,
                    readAll: "{{ route('notifications.read-all') }}",
                };

                function renderList(notifications) {
                    if (notifications.length === 0) {
                        list.innerHTML = '<div class="p-4 text-center text-xs text-gray-400">Aucune notification.</div>';
                        return;
                    }

                    list.innerHTML = notifications.map(n => `
                        <a href="${n.url ?? '#'}"
                           data-id="${n.id}"
                           class="notif-item block px-4 py-3 hover:bg-gray-50 transition ${n.read ? '' : 'bg-blue-50/50'}">
                            <div class="flex items-start gap-2.5">
                                <span class="text-lg leading-none">${n.icon}</span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-800 truncate">${n.title}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2">${n.body}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">${n.created_at}</p>
                                </div>
                                ${n.read ? '' : '<span class="w-2 h-2 rounded-full bg-blue-500 mt-1 flex-shrink-0"></span>'}
                            </div>
                        </a>
                    `).join('');

                    list.querySelectorAll('.notif-item').forEach(el => {
                        el.addEventListener('click', function() {
                            const id = this.dataset.id;
                            fetch(urls.read(id), {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                            });
                        });
                    });
                }

                function refreshBadge() {
                    fetch(urls.unreadCount, { headers: { 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(data => {
                            if (data.unread_count > 0) {
                                badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                                badge.classList.remove('hidden');
                            } else {
                                badge.classList.add('hidden');
                            }
                        })
                        .catch(() => {});
                }

                function loadList() {
                    fetch(urls.index, { headers: { 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(data => {
                            renderList(data.notifications);
                            refreshBadge();
                        })
                        .catch(() => {
                            list.innerHTML = '<div class="p-4 text-center text-xs text-red-400">Erreur de chargement.</div>';
                        });
                }

                markAllBtn.addEventListener('click', function() {
                    fetch(urls.readAll, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    }).then(() => loadList());
                });

                // Charger la liste au premier clic sur la cloche (lazy),
                // puis rafraîchir juste le badge en polling léger.
                document.addEventListener('DOMContentLoaded', function() {
                    refreshBadge();
                    setInterval(refreshBadge, 30000); // toutes les 30s

                    document.querySelectorAll('[x-data]').forEach(() => {}); // no-op, garde Alpine actif
                });

                // Charger la liste dès l'ouverture du dropdown
                document.body.addEventListener('click', function(e) {
                    const bellButton = e.target.closest('button');
                    if (bellButton && bellButton.parentElement?.querySelector('#notifList')) {
                        loadList();
                    }
                }, true);
            })();
        </script>
    @endpush
@endonce
