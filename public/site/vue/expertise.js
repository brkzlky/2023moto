var exp = new Vue({
    el: '#expertise',
    data: {
        brands: [],
        models: [],
        trims: [],
        selectedBrand: null,
        selectedModel: null,
        selectedTrim: null,
        fullname: null,
        email: null,
        phone: null,
        model_year: null,
        mileage: null,
        csrf: $('meta[name="csrf-token"]').attr('content'),
        lang: $('html')[0].lang,
        error: false,
    },
    methods: {
        setValue: function(e, type) {
            if(type == 'fullname') {
                this.fullname = e.target.value;
            }
            if(type == 'email') {
                this.email = e.target.value;
            }
            if(type == 'phone') {
                this.phone = e.target.value;
            }
            if(type == 'mileage') {
                this.mileage = e.target.value;
            }
            if(type == 'model_year') {
                this.model_year = e.target.value;
            }
        },
        applyExpertiseForm: function() {
            this.error = false;
            $('#offerNameSurname').removeClass('input-error');
            $('#expDate').removeClass('input-error');
            $('#expMilage').removeClass('input-error');
            $('#expMake').removeClass('input-error');
            $('#expModel').removeClass('input-error');
            $('#expVariant').removeClass('input-error');

            if(this.fullname == null || this.fullname == '' || this.fullname.trim() == '') {
                this.error = true;
                $('#offerNameSurname').addClass('input-error');
            }
            if(this.email == null || this.email == '' || this.email.trim() == '') {
                this.error = true;
                $('#offerEmail').addClass('input-error');
            }
            if(this.phone == null || this.phone == '' || this.phone.trim() == '') {
                this.error = true;
                $('#offerPhone').addClass('input-error');
            }
            if(this.model_year == null || this.model_year == '' || this.model_year.trim() == '') {
                this.error = true;
                $('#expDate').addClass('input-error');
            }
            if(this.mileage == null || this.mileage == '' || this.mileage.trim() == '') {
                this.error = true;
                $('#expMilage').addClass('input-error');
            }
            if(this.selectedBrand == null || this.selectedBrand == '' || this.selectedBrand.trim() == '') {
                this.error = true;
                $('#expMake').addClass('input-error');
            }
            if(this.selectedModel == null || this.selectedModel == '' || this.selectedModel.trim() == '') {
                this.error = true;
                $('#expModel').addClass('input-error');
            }
            if(this.selectedTrim == null || this.selectedTrim == '' || this.selectedTrim.trim() == '') {
                this.error = true;
                $('#expVariant').addClass('input-error');
            }

            if(!this.error) {
                $('#expForm').submit();
            }
        }
    },
    mounted() {
      setTimeout(() => {
        //exp.getBrands();
      }, 200);

      var self = this;
      setTimeout(() => {
        $('#expMake').autocomplete({
            source : function(requete, reponse){
                $.ajax({
                    type: "POST",
                    url : window.location.origin+'/expertise-brands',
                    dataType : 'json', 
                    data : {_token : self.csrf, key: $('#expMake').val()},
                    success : function(d){
                        reponse($.map(d, function(object){
                            return {
                                label: object.name,
                                value: object.brand_guid
                                };
                        }));
                    }
                });
            },
            focus: function(event, ui) {
                $("#expMake").val(ui.item.label);
                return false;
            },
            appendTo: '#expForm',
            minLength: 0,
            delay:300,
        
            select: function( event, ui ) {
                $('#expMake').val(  ui.item.label ); 
                $('#expMakeVal').val( ui.item.value ); 
                self.selectedBrand = ui.item.value;
                return false;
              } ,
        
            messages: {
                noResults: self.lang == 'ar' ? 'لم يتم العثور على نتائج' : 'No results found',
                results: function() {
                }
            },
        });

        $('#expMake').on('focus', function() {
            $('#expMake').autocomplete('search');
        });
        
        $('#expModel').autocomplete({
            source : function(request, reponse){
                $.ajax({
                    type: "POST",
                    url : window.location.origin+'/expertise-models',
                    dataType : 'json', 
                    data : {_token : self.csrf, key: $('#expModel').val(), brand: self.selectedBrand},
                    success : function(d){
        
                        reponse($.map(d, function(object){
                            return {
                                label: object.name,
                                value: object.model_guid
                                };
                        }));
                    }
                });
            },
            focus: function(event, ui) {
                $("#expModel").val(ui.item.label);
                return false;
            },
            appendTo: '#expForm',
            minLength: 0,
            delay:300,
        
            select: function( event, ui ) {
                $('#expModel').val(  ui.item.label ); 
                $('#expModelVal').val( ui.item.value ); 
                self.selectedModel = ui.item.value;
                return false;
            },
        
            messages: {
                noResults: self.lang == 'ar' ? 'لم يتم العثور على نتائج' : 'No results found',
                results: function() {}
            }
        });

        $('#expModel').on('focus', function() {
            $('#expModel').autocomplete('search');
        })

        $('#expVariant').autocomplete({
            source : function(requete, reponse){
                $.ajax({
                    type: "POST",
                    url : window.location.origin+'/expertise-trims',
                    dataType : 'json', 
                    data : {_token : self.csrf, key: $('#expVariant').val(), brand: self.selectedBrand, model: self.selectedModel, year: self.model_year},
                    success : function(d){
        
                        reponse($.map(d, function(object){
                            return {
                                label: object.name,
                                value: object.trim_guid
                                };
                        }));
                    }
                });
            },
            focus: function(event, ui) {
                $("#expVariant").val(ui.item.label);
                return false;
            },
            appendTo: '#expForm',
            minLength: 0,
            delay:300,
        
            select: function( event, ui ) {
                 $('#expVariant').val(  ui.item.label ); 
                 $('#expVariantVal').val( ui.item.value ); 
                 self.selectedTrim = ui.item.value;
                 return false;
              } ,
        
            messages: {
                noResults: self.lang == 'ar' ? 'لم يتم العثور على القطع الذي كنت تبحث عنه' : 'The trim you were looking for was not found',
                results: function() {}
            }
        });

        $('#expVariant').on('focus', function() {
            $('#expVariant').autocomplete('search');
        })
      }, 600);
    }
});