//iTop Custom Search Form Handler - based on search_form_handler 2.6.1. Added class, changed on success processing
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'search_form_handler' the widget name
	$.widget( 'itop.custom_search_form_handler', $.itop.search_form_handler,
	{
		
		// the constructor
		_create: function()
		{
			var me = this;
			
			// Turn string into function (PHP json_encode limitation)
			if(typeof this.options.onSubmitSuccess !== 'undefined') {
				eval('this.options.onSubmitSuccess = ' + this.options.onSubmitSuccess);
			}
			
			this.element.addClass('search_form_handler');

			// Init properties (complexe type properties would be static if not initialized with a simple type variable...)
			this.elements = {
				message_area: null,
				criterion_area: null,
				more_criterion: null,
				submit_button: null,
				results_area: null,
			};
			this.submit = {
				xhr: null,
			};

			//init others widgets :
			this.element.search_form_handler_history({'itop_root_class':me.options.search.class_name});


            // Prepare DOM elements
			this._prepareFormArea();
			this._prepareCriterionArea();
			this._prepareResultsArea();

			// Binding events (eg. from search_form_criteria widgets)
			this._bindEvents();

            //memorize the initial state so on first criteria close, we do not trigger a refresh if nothing has changed
            this._updateSearch();
			this.oPreviousAjaxParams = JSON.stringify({
                'base_oql': this.options.search.base_oql,
				'class': this.options.search.class,
                'criterion': this.options.search.criterion,
            });

		},
		
		// setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		setOptions: function()
		{
			this._superApply(arguments);
		},
		// setOption is called for each individual option that is changing
		setOption: function( key, value )
		{
			this._super( key, value );
		},

		// - Do the submit
		_submit: function(bAbortIfNoChange)
		{
			var me = this;

			// Data
			// - Regular params
			var oData = {
				'params': JSON.stringify({
					'base_oql': this.options.search.base_oql,
					'class': this.options.search.class_name,
					'criterion': this.options.search.criterion,
				}),
			};
			// - List params (pass through for the server), merge data_config with list_params if present.
			var oListParams = {};
			if(this.options.data_config_list_selector !== null)
			{
				var sExtraParams = $(this.options.data_config_list_selector).data('sExtraParams');
				if(sExtraParams !== undefined)
				{
					oListParams = JSON.parse(sExtraParams);
				}
			}
			$.extend(oListParams, this.options.list_params);
            if (me.element.parents('.ui-dialog').length !== 0)
            {
                oListParams.update_history = false;
            }
			oData.list_params = JSON.stringify(oListParams);

			if (true === bAbortIfNoChange)
			{
				if (typeof me.oPreviousAjaxParams == "undefined")
				{
                    me.oPreviousAjaxParams = oData.params;
					return;
				}

                if (me.oPreviousAjaxParams == oData.params)
                {
                    return;
                }
			}
            me.oPreviousAjaxParams = oData.params;

			// Abort pending request
			if(this.submit.xhr !== null)
			{
				this.submit.xhr.abort();
			}

			// Show loader
			this._showLoader();
			this._cleanMessageArea();

			// Do submit
			this.submit.xhr = $.post(
				this.options.endpoint,
				oData
			)
				.done(function(oResponse, sStatus, oXHR){ me._onSubmitSuccess(oResponse); })
				.fail(function(oResponse, sStatus, oXHR){ me._onSubmitFailure(oResponse, sStatus); })
				.always(function(oResponse, sStatus, oXHR){ me._onSubmitAlways(oResponse); });
		},
		
		// - Called on form submit successes
		_onSubmitSuccess: function(oData)
		{
			if(typeof this.options.onSubmitSuccess !== 'undefined') {
				this.options.onSubmitSuccess(oData);
			}
		},
		
	});
});
