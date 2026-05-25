<?php $__env->startSection('page-title', 'Edit Blood Sugar Record'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-2xl mx-auto px-4 sm:px-6">

    <div class="mb-6">
        <a href="<?php echo e(route('blood-sugar.index')); ?>" class="text-sm text-gray-500 hover:text-blue-600 transition">
            ← Back to Records
        </a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Blood Sugar Record</h2>
    </div>

    <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
            <ul class="list-disc list-inside text-sm space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">

        <form method="POST" action="<?php echo e(route('blood-sugar.update', $record->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                <select name="patient_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">— Select Patient —</option>
                    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($patient->id); ?>" <?php echo e(old('patient_id', $record->patient_id) == $patient->id ? 'selected' : ''); ?>>
                            <?php echo e($patient->patient_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Blood Sugar Level (mmol/L) <span class="text-red-500">*</span></label>
                <input type="number" step="0.1" name="blood_sugar_level"
                       value="<?php echo e(old('blood_sugar_level', $record->blood_sugar_level)); ?>"
                       placeholder="e.g. 5.4"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <p class="text-xs text-gray-400 mt-1">Normal: &lt;5.6 | Pre-diabetic: 5.6–6.9 | High: ≥7.0</p>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Measurement Type <span class="text-red-500">*</span></label>
                <select name="before_after" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Before Meal" <?php echo e(old('before_after', $record->before_after) == 'Before Meal' ? 'selected' : ''); ?>>Before Meal</option>
                    <option value="After Meal" <?php echo e(old('before_after', $record->before_after) == 'After Meal' ? 'selected' : ''); ?>>After Meal</option>
                </select>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="measurement_date"
                           value="<?php echo e(old('measurement_date', $record->measurement_date)); ?>"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time <span class="text-red-500">*</span></label>
                    <input type="time" name="measurement_time"
                           value="<?php echo e(old('measurement_time', $record->measurement_time)); ?>"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="notes" rows="3" placeholder="Any relevant observations..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('notes', $record->notes)); ?></textarea>
            </div>

            
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm text-gray-600">
                <p><strong>Recorded by:</strong> <?php echo e($record->measurement_by); ?></p>
                <p><strong>Created:</strong> <?php echo e($record->created_at->format('M d, Y H:i')); ?></p>
            </div>

            
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                    Update Record
                </button>
                <a href="<?php echo e(route('blood-sugar.index')); ?>"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium transition">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\myglucotrack-laravel-v3\resources\views/blood/edit.blade.php ENDPATH**/ ?>