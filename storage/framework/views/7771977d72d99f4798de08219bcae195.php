<?php $__env->startSection('page-title', '⚡ God Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">⚡ System Overview</h2>
        <p class="text-gray-500 text-sm mt-1">Complete view across all clinics</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500">Total Clinics</p>
            <p class="text-3xl font-bold text-purple-600 mt-1"><?php echo e($totalTenants); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500">Total Admins</p>
            <p class="text-3xl font-bold text-blue-600 mt-1"><?php echo e($totalAdmins); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500">Total Users</p>
            <p class="text-3xl font-bold text-green-600 mt-1"><?php echo e($totalUsers); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500">Total Patients</p>
            <p class="text-3xl font-bold text-yellow-600 mt-1"><?php echo e($totalPatients); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500">Total Records</p>
            <p class="text-3xl font-bold text-red-600 mt-1"><?php echo e($totalRecords); ?></p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold text-gray-800">Recently Registered Admins</h3>
            <a href="<?php echo e(route('god.admins')); ?>" class="text-sm text-blue-600 hover:underline font-medium">View All →</a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-left text-xs uppercase text-gray-500 tracking-wide">
                    <th class="pb-3 pr-4">Name</th>
                    <th class="pb-3 pr-4">Clinic</th>
                    <th class="pb-3 pr-4">Email</th>
                    <th class="pb-3 pr-4">Status</th>
                    <th class="pb-3">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $recentAdmins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 pr-4 font-medium text-gray-800"><?php echo e($admin->fullname); ?></td>
                    <td class="py-3 pr-4 text-gray-600"><?php echo e($admin->tenant->tenant_name ?? '—'); ?></td>
                    <td class="py-3 pr-4 text-gray-500"><?php echo e($admin->email); ?></td>
                    <td class="py-3 pr-4">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            <?php echo e($admin->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                            <?php echo e($admin->status); ?>

                        </span>
                    </td>
                    <td class="py-3 text-gray-400 text-xs"><?php echo e($admin->created_at->format('d M Y')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="py-8 text-center text-gray-400">No admins yet. <a href="<?php echo e(route('god.admins.create')); ?>" class="text-blue-600 hover:underline">Create one →</a></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\myglucotrack-laravel-v3\resources\views/god/dashboard.blade.php ENDPATH**/ ?>