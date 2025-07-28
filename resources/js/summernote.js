// summernote.js - Function to initialize the summernote editor.

/**
 * Initialize the summernote editor.
 *
 * @param {string} selector - The selector for the summernote editor.
 * @param {object} options - The options for the summernote editor.
 */
function initSummernote(selector, options) {
    if (selector === undefined) {
        selector = '.summernote-editor';
    }

    let defaultOptions = {
        placeholder: '...',
        height: 350,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear','strikethrough', 'superscript', 'subscript', 'fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['fontsize', ['fontsize']],
            ['view', ['codeview']],
        ],
        callbacks: {
            onChange: function(contents, $editable) {
                // Get the textarea/hidden input associated with the current editor.
                const $editor = $editable.closest('.note-editor');
                const $targetField = $editor.siblings('textarea, input[type="hidden"]');

                if ($targetField.length > 0) {
                    $targetField.val(contents);
                }
            }
        }
    };

    if (options === undefined) {
        options = defaultOptions;
    } else {
        options = { ...defaultOptions, ...options };
    }

    $(selector).summernote(options);
}

// Export the functions for use in other modules.
export { initSummernote };
