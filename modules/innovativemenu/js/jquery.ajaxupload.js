/*
* Just a simple jquery plugin wrapper of Andrew Valums great plugin:
* http://github.com/valums/file-uploader
* 
* Please use original script to unleash all its powers. This
* basically only works for the use-case where you have an element
* (i.e. a button or link) that should trigger opening of file dialog 
* and start upload after user has choosen a file.
* 
* Example:
*
* $('#upload-btn').ajaxUpload({
*		url: "/upload",
* 		complete: function(id, filename, json) {
*			console.log("Yay, uploaded file: " + filename);
*		}
* });
* 
* Author: Joel Soderstrom
*/
;(function($) {

    $.fn.ajaxUpload = function(opts) {
        var defaults = {
            url: '/',					// (required) URL where to upload
            allowedExtensions: [],		// Array of allowed file type, i.e. ["jpg", "jpeg", "gif", "png", "gif"]
            disableOnProgress: true,	// If upload of another file should be disabled while file is uloading
            complete: function(id, fileName, json) {},				// Callback when upload has finished
            progress: function(id, fileName, loaded, total) {},		// Callback on progress, i.e. show a spinner
			submit: function() {}									// Callback when file has been submitted
        };

        opts = $.extend(defaults, opts);

        return this.each(function() {
			var $this = $(this),
			uploader = new qq.FileUploaderBasic({
                button: $this[0],
                action: opts.url,
                allowedExtensions: opts.allowedExtensions,
                onComplete: function(id, fileName, json) {
                    if (opts.disableOnProgress) {
                        $this.unbind('click');
                    }
					// trigger global event "uploaded"
					$.event.trigger("uploaded", [id, fileName, json]);
                    opts.complete.call(this, id, fileName, json);
                },
                onProgress: opts.progress,
                onSubmit: function() {
                    if (opts.disableOnProgress) {
                        $this.click(function(e) {
                            e.preventDefault();
                        });
                    }
					opts.submit.call(this);
                }
            });
			$this.data('ajaxUpload', uploader);
        });

    };

})(jQuery);