<div class="flex flex-col h-full p-5">

    
    <div class="mb-8">
        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2">
            <span class="text-2xl">🩺</span>
            <div>
                <h2 class="text-lg font-bold text-blue-600 leading-tight">MyGlucoTrack</h2>
                <p class="text-xs text-gray-400">Health Monitoring System</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 space-y-1">

        
        <?php if(auth()->user()->role === 'System God'): ?>

            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 pb-1">⚡ God Panel</p>

            <a href="<?php echo e(route('god.dashboard')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
               <?php echo e(request()->routeIs('god.dashboard') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700'); ?>">
                <span>📊</span> System Overview
            </a>

            <a href="<?php echo e(route('god.admins')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
               <?php echo e(request()->routeIs('god.admins*') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700'); ?>">
                <span>👑</span> Manage Admins
            </a>

            <a href="<?php echo e(route('god.audit-logs')); ?>"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
                <?php echo e(request()->routeIs('god.audit-logs*') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700'); ?>">
                    <span>📜</span> Audit Logs
            </a>

            <div class="border-t border-gray-100 my-3"></div>

        <?php endif; ?>

        
        <?php if(auth()->user()->role !== 'System God'): ?>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 pb-1">Main Menu</p>
        <?php endif; ?>

        <a href="<?php echo e(route('dashboard')); ?>"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
           <?php echo e(request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'); ?>">
            <span>📊</span> Dashboard
        </a>

        <a href="<?php echo e(route('patients.index')); ?>"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
           <?php echo e(request()->routeIs('patients.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'); ?>">
            <span>👤</span> Patients
        </a>

        <a href="<?php echo e(route('blood-sugar.index')); ?>"
           class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
           <?php echo e(request()->routeIs('blood-sugar.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'); ?>">
            <span>🩸</span> Blood Sugar
        </a>

        
        <?php if(auth()->user()->role === 'Admin'): ?>
            <div class="border-t border-gray-100 my-3"></div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 pb-1">Admin</p>
            <a href="<?php echo e(route('admin.users')); ?>"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition
               <?php echo e(request()->routeIs('admin.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'); ?>">
                <span>👥</span> Manage Users
            </a>
        <?php endif; ?>

    </nav>

    <div class="border-t border-gray-100 my-4"></div>

    
    <div class="px-2">
        <a href="<?php echo e(route('profile.edit')); ?>" class="block hover:opacity-80 transition">
            <p class="text-xs text-gray-400 mb-1">Logged in as</p>
            <p class="text-sm font-semibold text-gray-700 truncate"><?php echo e(auth()->user()->fullname); ?></p>
            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-medium
                <?php echo e(auth()->user()->role === 'System God' ? 'bg-purple-100 text-purple-700' :
                   (auth()->user()->role === 'Admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600')); ?>">
                <?php echo e(auth()->user()->role); ?>

            </span>
            <p class="text-xs text-gray-400 mt-1"><?php echo e(auth()->user()->tenant->tenant_name ?? ''); ?></p>
        </a>
    </div>

</div>
<?php /**PATH C:\xampp\htdocs\myglucotrack-laravel-v3\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>