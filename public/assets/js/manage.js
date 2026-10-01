// ============================================
// Manage Product Page — Tags + Image Upload
// ============================================

document.addEventListener('DOMContentLoaded', function () {

  // --- Tags System ---
  var tagInput = document.getElementById('tagInput');
  var tagAddBtn = document.getElementById('tagAddBtn');
  var tagsContainer = document.getElementById('tagsContainer');
  var hiddenTags = document.getElementById('hiddenTags');

  function collectTags() {
    var chips = tagsContainer.querySelectorAll('.tag-chip-text');
    var tags = [];
    chips.forEach(function (chip) {
      tags.push(chip.textContent.trim());
    });
    if (hiddenTags) hiddenTags.value = tags.join(',');
  }

  function addTag(text) {
    var value = (text || '').trim();
    if (!value) return;

    // Prevent duplicates
    var existing = tagsContainer.querySelectorAll('.tag-chip-text');
    for (var i = 0; i < existing.length; i++) {
      if (existing[i].textContent.trim() === value) {
        tagInput.value = '';
        return;
      }
    }

    var chip = document.createElement('span');
    chip.className = 'tag-chip';
    chip.innerHTML =
      '<span class="tag-chip-text">' + escapeHtml(value) + '</span>' +
      '<button type="button" class="tag-chip-remove">&times;</button>';

    tagsContainer.appendChild(chip);
    tagInput.value = '';
    collectTags();
  }

  function removeTag(btn) {
    var chip = btn.closest('.tag-chip');
    if (chip) {
      chip.style.animation = 'tagChipIn 0.2s reverse';
      setTimeout(function () {
        chip.remove();
        collectTags();
      }, 180);
    }
  }

  if (tagAddBtn) {
    tagAddBtn.addEventListener('click', function () {
      addTag(tagInput.value);
    });
  }

  if (tagInput) {
    tagInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        addTag(tagInput.value);
      }
      if (e.key === 'Backspace' && tagInput.value === '') {
        var chips = tagsContainer.querySelectorAll('.tag-chip');
        if (chips.length > 0) {
          chips[chips.length - 1].remove();
          collectTags();
        }
      }
    });
  }

  // Delegate remove clicks
  if (tagsContainer) {
    tagsContainer.addEventListener('click', function (e) {
      if (e.target.classList.contains('tag-chip-remove')) {
        e.preventDefault();
        removeTag(e.target);
      }
    });
  }

  // Collect tags on form submit
  var manageForm = document.getElementById('manageForm');
  if (manageForm) {
    manageForm.addEventListener('submit', function () {
      collectTags();
    });
  }

  // --- Image Upload ---
  var fileInput = document.getElementById('fileInput');
  var uploadText = document.getElementById('uploadText');
  var uploadSub = document.getElementById('uploadSub');
  var uploadArea = document.querySelector('.upload-area');

  if (fileInput) {
    fileInput.addEventListener('change', function () {
      if (fileInput.files && fileInput.files.length > 0) {
        var file = fileInput.files[0];
        if (uploadText) uploadText.textContent = file.name;
        if (uploadSub) uploadSub.textContent = Math.round(file.size / 1024) + ' KB';

        // Show preview
        var existingPreview = uploadArea.querySelector('.upload-preview');
        if (existingPreview) existingPreview.remove();

        var reader = new FileReader();
        reader.onload = function (e) {
          var preview = document.createElement('div');
          preview.className = 'upload-preview';
          preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
          uploadArea.appendChild(preview);
        };
        reader.readAsDataURL(file);
      } else {
        if (uploadText) uploadText.textContent = 'اسحب صورة هنا أو اضغط للاختيار';
        if (uploadSub) uploadSub.textContent = 'PNG, JPG — حتى 5MB';
        var prev = uploadArea.querySelector('.upload-preview');
        if (prev) prev.remove();
      }
    });
  }

  // Drag & drop
  if (uploadArea) {
    uploadArea.addEventListener('dragover', function (e) {
      e.preventDefault();
      uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function () {
      uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function (e) {
      e.preventDefault();
      uploadArea.classList.remove('dragover');

      if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
        fileInput.files = e.dataTransfer.files;
        if (fileInput.onChange) fileInput.onChange();
        // Trigger change event manually
        var event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
      }
    });
  }

  // --- Clear All ---
  var clearBtn = document.getElementById('clearBtn');
  if (clearBtn) {
    clearBtn.addEventListener('click', function () {
      if (!confirm('هل أنت متأكد من مسح جميع الحقول؟')) return;

      var productName = document.getElementById('productName');
      var productTitle = document.getElementById('productTitle');
      var mainNumber = document.getElementById('mainNumber');
      var subNumber = document.getElementById('subNumber');

      if (productName) productName.value = '';
      if (productTitle) productTitle.value = '';
      if (mainNumber) mainNumber.value = '';
      if (subNumber) subNumber.value = '';
      if (tagInput) tagInput.value = '';

      // Clear tags
      if (tagsContainer) tagsContainer.innerHTML = '';
      collectTags();

      // Clear file
      if (fileInput) fileInput.value = '';
      if (uploadText) uploadText.textContent = 'اسحب صورة هنا أو اضغط للاختيار';
      if (uploadSub) uploadSub.textContent = 'PNG, JPG — حتى 5MB';
      var prev = uploadArea ? uploadArea.querySelector('.upload-preview') : null;
      if (prev) prev.remove();
    });
  }

  // --- Utility ---
  function escapeHtml(str) {
    var div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }

  // Collect initial tags if editing
  collectTags();
});
 