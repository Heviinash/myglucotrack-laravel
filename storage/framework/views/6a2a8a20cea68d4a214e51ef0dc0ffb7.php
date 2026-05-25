<?php $__env->startSection('page-title', 'Blood Sugar Records'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-7xl mx-auto">

    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Blood Sugar Records</h2>
            <p class="text-gray-500 text-sm mt-1">Track and monitor all blood sugar readings</p>
        </div>
        <a href="<?php echo e(route('blood-sugar.create')); ?>"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            + Add Record
        </a>
    </div>

    
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3 sm:p-4">
            <p class="text-xs text-gray-500 mb-1">Total Records</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-600"><?php echo e($totalRecords); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3 sm:p-4">
            <p class="text-xs text-gray-500 mb-1">Average Level</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600"><?php echo e(number_format($averageSugar, 1)); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3 sm:p-4">
            <p class="text-xs text-gray-500 mb-1">High Readings</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600"><?php echo e($highCount); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3 sm:p-4">
            <p class="text-xs text-gray-500 mb-1">Today's Records</p>
            <p class="text-xl sm:text-2xl font-bold text-yellow-600"><?php echo e($todayCount); ?></p>
        </div>
    </div>

    
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3 sm:p-4 mb-6">
        <form method="GET" action="<?php echo e(route('blood-sugar.index')); ?>" class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 items-start sm:items-end">
            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">Patient</label>
                <select name="patient_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Patients</option>
                    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($patient->id); ?>" <?php echo e(request('patient_id') == $patient->id ? 'selected' : ''); ?>>
                            <?php echo e($patient->patient_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">From Date</label>
                <input type="date" name="from_date" value="<?php echo e(request('from_date')); ?>"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">To Date</label>
                <input type="date" name="to_date" value="<?php echo e(request('to_date')); ?>"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit"
                        class="flex-1 sm:flex-none bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    Filter
                </button>
                <?php if(request()->hasAny(['patient_id','from_date','to_date'])): ?>
                    <a href="<?php echo e(route('blood-sugar.index')); ?>"
                       class="flex-1 sm:flex-none text-center text-sm text-gray-500 hover:text-gray-700 px-3 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        Clear
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs uppercase text-gray-500 tracking-wide">
                        <th class="px-4 sm:px-5 py-3">Patient</th>
                        <th class="px-4 sm:px-5 py-3">Level (mmol/L)</th>
                        <th class="hidden sm:table-cell px-4 sm:px-5 py-3">Status</th>
                        <th class="hidden md:table-cell px-4 sm:px-5 py-3">Type</th>
                        <th class="hidden lg:table-cell px-4 sm:px-5 py-3">Measured By</th>
                        <th class="px-4 sm:px-5 py-3">Date & Time</th>
                        <th class="px-4 sm:px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 sm:px-5 py-3 font-medium text-gray-700">
                                <?php echo e($record->patient->patient_name ?? '—'); ?>

                            </td>
                            <td class="px-4 sm:px-5 py-3 font-semibold text-gray-900">
                                <?php echo e($record->blood_sugar_level); ?>

                            </td>
                            <td class="hidden sm:table-cell px-4 sm:px-5 py-3">
                                <?php if($record->blood_sugar_level < 5.6): ?>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">🟢 Normal</span>
                                <?php elseif($record->blood_sugar_level < 7): ?>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">🟡 Pre-Diabetic</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">🔴 High</span>
                                <?php endif; ?>
                            </td>
                            <td class="hidden md:table-cell px-4 sm:px-5 py-3 text-gray-500"><?php echo e($record->before_after); ?></td>
                            <td class="hidden lg:table-cell px-4 sm:px-5 py-3 text-gray-500"><?php echo e($record->measurement_by ?? '—'); ?></td>
                            <td class="px-4 sm:px-5 py-3 text-gray-500 text-xs sm:text-sm">
                                <?php echo e(\Carbon\Carbon::parse($record->measurement_date)->format('d M Y')); ?>

                                <span class="text-xs text-gray-400 block"><?php echo e($record->measurement_time); ?></span>
                            </td>
                            <td class="px-4 sm:px-5 py-3 text-right sm:text-left">
                                <div class="flex gap-2 flex-col sm:flex-row">
                                    <a href="<?php echo e(route('blood-sugar.edit', $record->id)); ?>"
                                       class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                                    <form action="<?php echo e(route('blood-sugar.destroy', $record->id)); ?>"
                                          method="POST" class="inline"
                                          onsubmit="return confirm('Delete this record?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-4 sm:px-5 py-10 text-center text-gray-400">
                                No records found.
                                <a href="<?php echo e(route('blood-sugar.create')); ?>" class="text-blue-600 hover:underline ml-1">Add first record →</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\myglucotrack-laravel-v3\resources\views/blood/index.blade.php ENDPATH**/ ?>