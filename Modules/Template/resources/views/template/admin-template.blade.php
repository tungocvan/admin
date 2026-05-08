<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProManage - Project Tool</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .kanban-column {
            min-height: 500px;
        }
        .ghost-card {
            opacity: 0.5;
            background: #f3f4f6;
            border: 2px dashed #9ca3af;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-8">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <i data-lucide="layout-grid" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight">ProManage</span>
                </div>
                <nav class="space-y-2">
                    <button onclick="switchView('board')" id="nav-board" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-blue-600 text-white transition-all">
                        <i data-lucide="trello" class="w-5 h-5"></i>
                        <span class="font-medium">Task Board</span>
                    </button>
                    <button onclick="switchView('dashboard')" id="nav-dashboard" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 transition-all">
                        <i data-lucide="pie-chart" class="w-5 h-5"></i>
                        <span class="font-medium">Analytics</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 transition-all">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span class="font-medium">Team</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 text-slate-400 transition-all">
                        <i data-lucide="settings" class="w-5 h-5"></i>
                        <span class="font-medium">Settings</span>
                    </button>
                </nav>
            </div>
            <div class="mt-auto p-6 border-t border-slate-800">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name=Alex+Doe&background=2563eb&color=fff" class="w-10 h-10 rounded-full" alt="User">
                    <div>
                        <p class="text-sm font-semibold">Alex Doe</p>
                        <p class="text-xs text-slate-400">Project Manager</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="h-16 border-b border-slate-200 bg-white flex items-center justify-between px-8 z-10">
                <div class="flex items-center gap-4 flex-1 max-w-xl">
                    <div class="relative w-full">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="searchInput" placeholder="Search tasks..." class="w-full pl-10 pr-4 py-2 bg-slate-100 border-none rounded-lg text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <select id="priorityFilter" class="bg-slate-100 border-none rounded-lg text-sm px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition-all">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        New Task
                    </button>
                    <div class="w-px h-6 bg-slate-200"></div>
                    <button class="relative text-slate-500 hover:text-slate-900 transition-all">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                </div>
            </header>

            <!-- Board View -->
            <div id="board-view" class="flex-1 overflow-x-auto p-8 custom-scrollbar">
                <div class="flex gap-6 min-w-max h-full">
                    <!-- Column: To Do -->
                    <div class="w-80 flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-slate-700 uppercase tracking-wider text-xs">To Do</h3>
                                <span id="todo-count" class="bg-slate-200 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                            </div>
                            <button class="text-slate-400 hover:text-slate-600"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
                        </div>
                        <div id="todo-list" class="kanban-column flex-1 space-y-4" data-status="todo"></div>
                    </div>

                    <!-- Column: In Progress -->
                    <div class="w-80 flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-slate-700 uppercase tracking-wider text-xs text-blue-600">In Progress</h3>
                                <span id="inprogress-count" class="bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                            </div>
                            <button class="text-slate-400 hover:text-slate-600"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
                        </div>
                        <div id="inprogress-list" class="kanban-column flex-1 space-y-4" data-status="inprogress"></div>
                    </div>

                    <!-- Column: Done -->
                    <div class="w-80 flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-slate-700 uppercase tracking-wider text-xs text-emerald-600">Done</h3>
                                <span id="done-count" class="bg-emerald-100 text-emerald-600 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                            </div>
                            <button class="text-slate-400 hover:text-slate-600"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
                        </div>
                        <div id="done-list" class="kanban-column flex-1 space-y-4" data-status="done"></div>
                    </div>
                </div>
            </div>

            <!-- Dashboard View (Hidden by default) -->
            <div id="dashboard-view" class="hidden flex-1 overflow-y-auto p-8 custom-scrollbar">
                <h2 class="text-2xl font-bold mb-6">Project Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <p class="text-slate-500 text-sm mb-1">Total Tasks</p>
                        <p id="stat-total" class="text-3xl font-bold">0</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <p class="text-slate-500 text-sm mb-1">Completion Rate</p>
                        <p id="stat-rate" class="text-3xl font-bold text-emerald-600">0%</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <p class="text-slate-500 text-sm mb-1">In Progress</p>
                        <p id="stat-progress" class="text-3xl font-bold text-blue-600">0</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <p class="text-slate-500 text-sm mb-1">High Priority</p>
                        <p id="stat-high" class="text-3xl font-bold text-rose-600">0</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold mb-4">Task Distribution</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-slate-600">To Do</span>
                                    <span id="dist-todo-val" class="font-semibold">0%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div id="dist-todo-bar" class="bg-slate-400 h-2 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-slate-600">In Progress</span>
                                    <span id="dist-progress-val" class="font-semibold">0%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div id="dist-progress-bar" class="bg-blue-500 h-2 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-slate-600">Completed</span>
                                    <span id="dist-done-val" class="font-semibold">0%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div id="dist-done-bar" class="bg-emerald-500 h-2 rounded-full" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold mb-4">Team Activity</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=Sarah+Wilson&background=ec4899&color=fff" class="w-8 h-8 rounded-full">
                                <div class="flex-1">
                                    <p class="text-sm font-medium">Sarah Wilson</p>
                                    <p class="text-xs text-slate-500">Updated "Home Page Redesign"</p>
                                </div>
                                <span class="text-xs text-slate-400">2m ago</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=John+Dev&background=8b5cf6&color=fff" class="w-8 h-8 rounded-full">
                                <div class="flex-1">
                                    <p class="text-sm font-medium">John Dev</p>
                                    <p class="text-xs text-slate-500">Moved "API Integration" to Done</p>
                                </div>
                                <span class="text-xs text-slate-400">1h ago</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=Mike+Ross&background=f59e0b&color=fff" class="w-8 h-8 rounded-full">
                                <div class="flex-1">
                                    <p class="text-sm font-medium">Mike Ross</p>
                                    <p class="text-xs text-slate-500">Added a comment to "User Auth"</p>
                                </div>
                                <span class="text-xs text-slate-400">3h ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal: New Task -->
    <div id="taskModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-xl font-bold">Create New Task</h2>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-all">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <form id="taskForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Title</label>
                    <input type="text" id="taskTitle" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                    <textarea id="taskDesc" rows="3" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Priority</label>
                        <select id="taskPriority" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Due Date</label>
                        <input type="date" id="taskDue" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Assign To</label>
                    <select id="taskAssignee" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        <option value="Alex Doe">Alex Doe (You)</option>
                        <option value="Sarah Wilson">Sarah Wilson</option>
                        <option value="John Dev">John Dev</option>
                        <option value="Mike Ross">Mike Ross</option>
                    </select>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-6 py-2 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-all font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-medium">Create Task</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initial state
        const defaultTasks = [
            { id: 1, title: "Initial project research", description: "Gather stakeholder requirements and market research.", priority: "medium", dueDate: "2023-12-01", assignee: "Alex Doe", status: "done" },
            { id: 2, title: "Design system architecture", description: "Define the tech stack and database schema.", priority: "high", dueDate: "2023-12-15", assignee: "John Dev", status: "inprogress" },
            { id: 3, title: "User interface mockups", description: "Create high-fidelity designs for main dashboard and mobile views.", priority: "medium", dueDate: "2023-12-20", assignee: "Sarah Wilson", status: "todo" },
            { id: 4, title: "API Documentation", description: "Write comprehensive docs for the internal REST API.", priority: "low", dueDate: "2024-01-05", assignee: "Mike Ross", status: "todo" }
        ];

        let tasks = JSON.parse(localStorage.getItem('promanage_tasks')) || defaultTasks;

        function saveTasks() {
            localStorage.setItem('promanage_tasks', JSON.stringify(tasks));
        }

        let currentSearch = "";
        let currentPriorityFilter = "all";

        // Initialize Lucide icons
        function updateIcons() {
            lucide.createIcons();
        }

        // Render Tasks
        function renderTasks() {
            const columns = {
                todo: document.getElementById('todo-list'),
                inprogress: document.getElementById('inprogress-list'),
                done: document.getElementById('done-list')
            };

            // Clear columns
            Object.values(columns).forEach(col => col.innerHTML = '');

            // Filter tasks
            const filteredTasks = tasks.filter(task => {
                const matchesSearch = task.title.toLowerCase().includes(currentSearch.toLowerCase()) ||
                                     task.description.toLowerCase().includes(currentSearch.toLowerCase());
                const matchesPriority = currentPriorityFilter === 'all' || task.priority === currentPriorityFilter;
                return matchesSearch && matchesPriority;
            });

            // Populate columns
            filteredTasks.forEach(task => {
                const taskEl = createTaskElement(task);
                columns[task.status].appendChild(taskEl);
            });

            // Update counts
            document.getElementById('todo-count').innerText = filteredTasks.filter(t => t.status === 'todo').length;
            document.getElementById('inprogress-count').innerText = filteredTasks.filter(t => t.status === 'inprogress').length;
            document.getElementById('done-count').innerText = filteredTasks.filter(t => t.status === 'done').length;

            updateStats();
            updateIcons();
        }

        function createTaskElement(task) {
            const card = document.createElement('div');
            card.className = 'bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all cursor-grab active:cursor-grabbing group';
            card.setAttribute('data-id', task.id);

            const priorityColors = {
                high: 'bg-rose-100 text-rose-600',
                medium: 'bg-amber-100 text-amber-600',
                low: 'bg-blue-100 text-blue-600'
            };

            const assigneeInitials = task.assignee.split(' ').map(n => n[0]).join('');

            card.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full ${priorityColors[task.priority]}">
                        ${task.priority}
                    </span>
                    <button onclick="deleteTask(${task.id})" class="text-slate-300 hover:text-rose-500 opacity-0 group-hover:opacity-100 transition-all">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
                <h4 class="font-semibold text-slate-800 mb-1 line-clamp-2">${task.title}</h4>
                <p class="text-xs text-slate-500 mb-4 line-clamp-2">${task.description || 'No description provided.'}</p>
                <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                    <div class="flex items-center gap-1.5 text-slate-400">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                        <span class="text-[11px] font-medium">${task.dueDate ? formatDate(task.dueDate) : 'No date'}</span>
                    </div>
                    <div class="flex -space-x-2">
                        <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center border-2 border-white ring-1 ring-slate-100" title="${task.assignee}">
                            <span class="text-[10px] text-white font-bold">${assigneeInitials}</span>
                        </div>
                    </div>
                </div>
            `;
            return card;
        }

        function formatDate(dateStr) {
            const options = { month: 'short', day: 'numeric' };
            return new Date(dateStr).toLocaleDateString(undefined, options);
        }

        // Drag and Drop Logic
        function initSortable() {
            const containers = ['todo-list', 'inprogress-list', 'done-list'];
            containers.forEach(id => {
                new Sortable(document.getElementById(id), {
                    group: 'tasks',
                    ghostClass: 'ghost-card',
                    animation: 200,
                    onEnd: (evt) => {
                        const taskId = parseInt(evt.item.getAttribute('data-id'));
                        const newStatus = evt.to.getAttribute('data-status');

                        // Update status in state
                        const taskIndex = tasks.findIndex(t => t.id === taskId);
                        if (taskIndex !== -1) {
                            tasks[taskIndex].status = newStatus;
                            saveTasks();
                            renderTasks();
                        }
                    }
                });
            });
        }

        // Modal Controls
        function openModal() {
            document.getElementById('taskModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('taskModal').classList.add('hidden');
            document.getElementById('taskForm').reset();
        }

        // Task Actions
        function deleteTask(id) {
            tasks = tasks.filter(t => t.id !== id);
            saveTasks();
            renderTasks();
        }

        document.getElementById('taskForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const newTask = {
                id: Date.now(),
                title: document.getElementById('taskTitle').value,
                description: document.getElementById('taskDesc').value,
                priority: document.getElementById('taskPriority').value,
                dueDate: document.getElementById('taskDue').value,
                assignee: document.getElementById('taskAssignee').value,
                status: 'todo'
            };
            tasks.push(newTask);
            saveTasks();
            closeModal();
            renderTasks();
        });

        // Search & Filter
        document.getElementById('searchInput').addEventListener('input', (e) => {
            currentSearch = e.target.value;
            renderTasks();
        });

        document.getElementById('priorityFilter').addEventListener('change', (e) => {
            currentPriorityFilter = e.target.value;
            renderTasks();
        });

        // View Switching
        function switchView(view) {
            const board = document.getElementById('board-view');
            const dash = document.getElementById('dashboard-view');
            const navBoard = document.getElementById('nav-board');
            const navDash = document.getElementById('nav-dashboard');

            if (view === 'board') {
                board.classList.remove('hidden');
                dash.classList.add('hidden');
                navBoard.classList.add('bg-blue-600', 'text-white');
                navBoard.classList.remove('hover:bg-slate-800', 'text-slate-400');
                navDash.classList.remove('bg-blue-600', 'text-white');
                navDash.classList.add('hover:bg-slate-800', 'text-slate-400');
            } else {
                board.classList.add('hidden');
                dash.classList.remove('hidden');
                navDash.classList.add('bg-blue-600', 'text-white');
                navDash.classList.remove('hover:bg-slate-800', 'text-slate-400');
                navBoard.classList.remove('bg-blue-600', 'text-white');
                navBoard.classList.add('hover:bg-slate-800', 'text-slate-400');
                updateStats();
            }
        }

        // Stats Calculation
        function updateStats() {
            const total = tasks.length;
            const done = tasks.filter(t => t.status === 'done').length;
            const progress = tasks.filter(t => t.status === 'inprogress').length;
            const todo = tasks.filter(t => t.status === 'todo').length;
            const high = tasks.filter(t => t.priority === 'high').length;

            const rate = total === 0 ? 0 : Math.round((done / total) * 100);

            // Update Stat Cards
            document.getElementById('stat-total').innerText = total;
            document.getElementById('stat-rate').innerText = `${rate}%`;
            document.getElementById('stat-progress').innerText = progress;
            document.getElementById('stat-high').innerText = high;

            // Update Chart Bars
            if (total > 0) {
                const todoPct = Math.round((todo / total) * 100);
                const progPct = Math.round((progress / total) * 100);
                const donePct = Math.round((done / total) * 100);

                document.getElementById('dist-todo-val').innerText = `${todoPct}%`;
                document.getElementById('dist-todo-bar').style.width = `${todoPct}%`;

                document.getElementById('dist-progress-val').innerText = `${progPct}%`;
                document.getElementById('dist-progress-bar').style.width = `${progPct}%`;

                document.getElementById('dist-done-val').innerText = `${donePct}%`;
                document.getElementById('dist-done-bar').style.width = `${donePct}%`;
            }
        }

        // Initialize
        window.onload = () => {
            renderTasks();
            initSortable();
            updateIcons();
        };
    </script>
</body>
</html>
