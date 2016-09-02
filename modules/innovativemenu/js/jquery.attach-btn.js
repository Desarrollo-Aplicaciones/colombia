/*!
 * 
 * Usage:
 * 
 * $('#hiddenInput').attachBtn();
 * 
 * <input type="hidden" value="{id:1, filename:'fooo'}" id="hiddenInput" />
 *
 * Author: Joel Soderstrom
 * @requries jquery.ajax-upload.js
 */
;(function($) {
	
    var defaults = {
		model: undefined,
		deleteUrl: "/delete",		// String or function that returns string with model as arg
		uploadUrl: "/upload", 		// String or function that returns string
		fileUrl:   function(model){}, 	// Function that returns string with model as arg
		uploadBtnTemplate: '<span class="btn upload">{uploadMsg}</span>',
		uploadedTemplate: '<div><a href="{fileUrl}" class="filename">{filename}</a><a class="del btn small" href="{deleteUrl}">{deleteMsg}</a></div>',
		loadingTemplate: '<img src="loading.gif" alt="Loading..." />',
		uploadMsg: "Upload",
		deleteMsg: "Delete",
		deleteConfirmMsg: "Are you sure you want to delete file?"
	};
	
	function Plugin(el, opts) {
		this.opts = $.extend({}, defaults, opts);
		this.el = el;
		
		// decide what state to render based on model
		$.isEmptyObject(this.opts.model) ? renderEmpty.call(this) : renderUploaded.call(this);
	};
	
	/**
	 * Render state "empty"
	 */
	function renderEmpty() {
		var oThis = this,
			uploadUrl,
			uploadBtn = $(this.opts.uploadBtnTemplate).text(this.opts.uploadMsg);
		
		// clear any existing child els and add button to container
		this.el.empty().append(uploadBtn);
		
		// upload URL can be either a string or a function
		uploadUrl = getVal(this.opts.uploadUrl);
		
		// init ajax upload
		uploadBtn.ajaxUpload({
			url: uploadUrl,
			submit: function() {
				renderLoading.call(oThis);
			},
			complete: function(id, filename, json) {
				oThis.opts.model = json;
				renderUploaded.call(oThis, filename);
			}
		});
	}
	
	/**
	 * Render state "uploaded"
	 */ 
	function renderUploaded(filename) {
		var uploadedTemplate = $(this.opts.uploadedTemplate),
			fileUrl = getVal(this.opts.fileUrl, this.opts.model),
			deleteUrl = getVal(this.opts.deleteUrl, this.opts.model);
		
		uploadedTemplate
			.find('.filename')
			.attr('href', fileUrl)
			.text(filename ? filename : this.opts.model.filename)
		.end()
			.find('.del')
			.attr('href', deleteUrl)
			.text(this.opts.deleteMsg)
			.bind('click', { oThis: this }, deleteFile);
		
		this.el.empty().append(uploadedTemplate);
	}
	
	/**
	 * Render state "loading"
	 */
	function renderLoading() {
		var loading = $(this.opts.loadingTemplate);
		this.el.empty().append(loading);
	}
	
	/**
	 * Call "delete" on server and trigger rendering
	 * of new view when done.
	 */
	function deleteFile(e) {
		if(confirm(e.data.oThis.opts.deleteConfirmMsg)) {
			$.ajax({
				url: $(this).attr('href'),
				success: function() {
					renderEmpty.call(e.data.oThis);
				}
			});
		}
		return false;
	}
	
	function getVal(objOrFunc, arg) {
		return isFunction(objOrFunc) ? objOrFunc(arg) : objOrFunc;
	}
	
	/**
	 * Helper method to see if given argument is a function.
	 * Based on this answer: http://stackoverflow.com/a/7356528/83592
	 */ 
	function isFunction(functionToCheck) {
		return functionToCheck && {}.toString.call(functionToCheck) == '[object Function]';
	}

    $.fn.attachBtn = function(opts) {
        return this.each(function() {
			new Plugin($(this), opts);
        });
    };
	
})(jQuery);

