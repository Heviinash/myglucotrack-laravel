<?php $__env->startSection('page-title', 'Edit Patient'); ?>
<?php $__env->startSection('content'); ?>

<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <a href="<?php echo e(route('patients.index')); ?>" class="text-sm text-gray-500 hover:text-blue-600 transition">← Back to Patients</a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Patient</h2>
    </div>

    <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="<?php echo e(route('patients.update', $patient->id)); ?>" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="patient_name" value="<?php echo e(old('patient_name', $patient->patient_name)); ?>"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">IC Number (MyKad) <span class="text-red-500">*</span></label>
                <input type="text" name="ic_number" id="ic_number"
                       value="<?php echo e(old('ic_number', $patient->ic_number)); ?>"
                       maxlength="12"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       oninput="this.value=this.value.replace(/\D/g,'')"
                       onchange="autoFillFromIC(this.value)"
                       required>
                <p class="text-xs text-gray-400 mt-1">12 digits only. DOB and age will auto-update.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <input type="text" id="dob_display" readonly
                           value="<?php echo e($patient->dob ? $patient->dob->format('d M Y') : ''); ?>"
                           class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Age</label>
                    <input type="text" id="age_display" readonly
                           value="<?php echo e($patient->age ? $patient->age . ' years old' : ''); ?>"
                           class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-500 cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                <select name="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">— Select —</option>
                    <option value="Male"   <?php echo e(old('gender', $patient->gender) === 'Male'   ? 'selected' : ''); ?>>Male</option>
                    <option value="Female" <?php echo e(old('gender', $patient->gender) === 'Female' ? 'selected' : ''); ?>>Female</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                <input type="text" name="phone" value="<?php echo e(old('phone', $patient->phone)); ?>"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="address" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('address', $patient->address)); ?></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                    Update Patient
                </button>
                <a href="<?php echo e(route('patients.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<script>
function autoFillFromIC(ic) {
    if (ic.length !== 12) return;
    const year2  = parseInt(ic.substring(0, 2));
    const month  = parseInt(ic.substring(2, 4));
    const day    = parseInt(ic.substring(4, 6));
    const currentYear2 = parseInt(new Date().getFullYear().toString().slice(-2));
    const fullYear = year2 <= currentYear2 ? 2000 + year2 : 1900 + year2;
    if (month < 1 || month > 12 || day < 1 || day > 31) return;
    const dob  = new Date(fullYear, month - 1, day);
    const now  = new Date();
    let age    = now.getFullYear() - dob.getFullYear();
    const m    = now.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && now.getDate() < dob.getDate())) age--;
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    document.getElementById('dob_display').value = `${String(day).padStart(2,'0')} ${months[month-1]} ${fullYear}`;
    document.getElementById('age_display').value  = age + ' years old';
    const lastDigit = parseInt(ic.substring(11, 12));
    const genderSel = document.querySelector('select[name="gender"]');
    if (genderSel) genderSel.value = lastDigit % 2 !== 0 ? 'Male' : 'Female';
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\myglucotrack-laravel-v3\resources\views/patients/edit.blade.php ENDPATH**/ ?>