// ============================================================================
// General Functions
// ============================================================================

function toggle_visibility(target) {
    var element = document.getElementById(target);
    if (element.style.visibility=='visible') {
        element.style.visibility = 'hidden';
    }
    else
        element.style.visibility = 'visible';
}

function toggleAll(source, target) {
    document.querySelectorAll('input[name="' + target + '"]')
        .forEach(cb => cb.checked = source.checked);
}

function toggleAllForNameStartedWith(source, nameStartedWith) {
    document.querySelectorAll('input[name^="'+ nameStartedWith +'"]').forEach(cb => {
        cb.checked = source.checked;
    });
}

function changeButtonTextAfterClickThenChangeItBack(source, message) {
    const originalText = source.innerText;
    source.innerText = message;

    setTimeout(() => {
        source.innerText = originalText;
    }, 2000);
}

// ============================================================================
// Page Load (jQuery)
// ============================================================================

$(() => {

    // Autofocus
    $('form :input:not(button):first').focus();
    $('.err:first').prev().focus();
    $('.err:first').prev().find(':input:first').focus();
    
    // Confirmation message
    // TODO
    $('[data-confirm]').on('click', e => {
        const text = e.target.dataset.confirm || 'Are you sure?';
        if (!confirm(text)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    })

    // Initiate GET request
    $('[data-get]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.get;
        location = url || location;
    });

    // Initiate POST request
    $('[data-post]').on('click', e => {
        e.preventDefault();
        const url = e.target.dataset.post;
        const f = $('<form>').appendTo(document.body)[0];
        f.method = 'POST';
        f.action = url || location;
        f.submit();
    });

    // Reset form
    $('[type=reset]').on('click', e => {
        e.preventDefault();
        location = location;
    });

    // Auto uppercase
    $('[data-upper]').on('input', e => {
        const a = e.target.selectionStart;
        const b = e.target.selectionEnd;
        e.target.value = e.target.value.toUpperCase();
        e.target.setSelectionRange(a, b);
    });

    // Photo preview
    $('label.upload input[type=file]').on('change', e => {
        const f = e.target.files[0];
        const img = $(e.target).siblings('img')[0];

        if (!img) return;

        img.dataset.src ??= img.src;

        if (f?.type.startsWith('image/')) {
            img.src = URL.createObjectURL(f);
        }
        else {
            img.src = img.dataset.src;
            e.target.value = '';
        }
    });
});