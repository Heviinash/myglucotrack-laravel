<?php $__env->startSection('page-title', 'Manage Admins'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">👑 Manage Admins</h2>
            <p class="text-gray-500 text-sm mt-1">All clinic admins registered in the system</p>
        </div>
        <a href="<?php echo e(route('god.admins.create')); ?>"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            + New Admin &amp; Clinic
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs uppercase text-gray-500 tracking-wide">
                        <th class="px-5 py-3">#</th>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Clinic</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Patients</th>
                        <th class="px-5 py-3">Records</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Joined</th>
                        <th class="px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-400"><?php echo e($loop->iteration); ?></td>
                        <td class="px-5 py-3 font-medium text-gray-800"><?php echo e($admin->fullname); ?></td>
                        <td class="px-5 py-3 text-gray-600"><?php echo e($admin->tenant->tenant_name ?? '—'); ?></td>
                        <td class="px-5 py-3 text-gray-500"><?php echo e($admin->email); ?></td>
                        <td class="px-5 py-3 text-gray-600"><?php echo e($admin->patient_count); ?></td>
                        <td class="px-5 py-3 text-gray-600"><?php echo e($admin->record_count); ?></td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                <?php echo e($admin->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                                <?php echo e($admin->status); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs"><?php echo e($admin->created_at->format('d M Y')); ?></td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                
                                <a href="<?php echo e(route('god.admins.edit', $admin->id)); ?>"
                                   class="text-xs font-medium text-blue-600 hover:text-blue-800">Edit</a>
                                
                                <form action="<?php echo e(route('god.admins.toggle', $admin->id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button class="text-xs font-medium
                                        <?php echo e($admin->status === 'Active' ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800'); ?>">
                                        <?php echo e($admin->status === 'Active' ? 'Disable' : 'Enable'); ?>

                                    </button>
                                </form>
                                
                                <a href="<?php echo e(route('god.admins.reset-password', $admin->id)); ?>"
                                   class="text-xs font-medium text-gray-500 hover:text-gray-700">Reset PW</a>
                                
                                <form action="<?php echo e(route('god.admins.destroy', $admin->id)); ?>" method="POST" class="inline"
                                      onsubmit="return confirm('Delete <?php echo e($admin->fullname); ?> and ALL their clinic data? This cannot be undone.')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="px-5 py-10 text-center text-gray-400">
                            No admins yet. <a href="<?php echo e(route('god.admins.create')); ?>" class="text-blue-600 hover:underline">Create one →</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\myglucotrack-laravel-v3\resources\views/god/admins.blade.php ENDPATH**/ ?>