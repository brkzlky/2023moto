var fnc = new Vue({
    el: '#finance',
    data: {
        rates: [],
        loanresult: null,
        loan: $('#loanvalue').val(),
        maturity: "",
        recomends: [],
        recomendpath: null,
        alertMsg: null,
        calcLoanErr: false,
        calcMaturityErr: false,
        calcClicked: false,
        lang: $('html')[0].lang,
        monthNames: ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ]
    },
    methods: {
        getRates: function() {
            axios.post(window.location.origin+'/finance-rates').then((d) => {
            if(d.status == 200) {
                if(d.data.result == 200) {
                    this.rates = [];
                    this.rates = d.data.rates;
                }
            }
            })
        },
        calcLoan: function() {
            this.recomends = [];
            this.loanresult = null;
            this.recomendpath = null;
            if(this.loan == null) {
                if(this.maturity == "") {
                    this.calcLoanErr = true;
                    this.calcMaturityErr = true;
                    $('.js-calc-offer-container').slideUp(200, 'linear');
                    $('.js-filter__card-container').slideUp(200, 'linear');
                } else {
                    this.calcLoanErr = true;
                    this.calcMaturityErr = false;
                    $('.js-calc-offer-container').slideUp(200, 'linear');
                    $('.js-filter__card-container').slideUp(200, 'linear');
                }
            } else {
                if(this.loan == 0) {
                    if(this.maturity == "") {
                        this.calcLoanErr = true;
                        this.calcMaturityErr = true;
                        $('.js-calc-offer-container').slideUp(200, 'linear');
                        $('.js-filter__card-container').slideUp(200, 'linear');
                    } else {
                        this.calcLoanErr = true;
                        this.calcMaturityErr = false;
                        $('.js-calc-offer-container').slideUp(200, 'linear');
                        $('.js-filter__card-container').slideUp(200, 'linear');
                    }
                } else {
                    if(this.maturity == "") {
                        this.calcLoanErr = false;
                        this.calcMaturityErr = true;
                        $('.js-calc-offer-container').slideUp(200, 'linear');
                        $('.js-filter__card-container').slideUp(200, 'linear');
                    } else {
                        this.calcClicked = true;
                        this.calcLoanErr = false;
                        this.calcMaturityErr = false;

                        var postdata = new FormData();
                        postdata.append('loan', this.loan);
                        postdata.append('maturity', this.maturity);
                        axios.post(window.location.origin+'/mtvg-calculate-loan', postdata).then((d) => {
                            if(d.status == 200) {
                                if(d.data.result == 200) {
                                    this.calcClicked = false;
                                    this.loanresult = d.data.loanresult;
                                    this.recomends = d.data.recomendeds;
                                    this.recomendpath = d.data.recomendpath;
                                    $('.js-calc-offer-container').slideDown(200, 'linear');
                                    setTimeout(() => {
                                        $('.js-filter__card-container').slideDown(200, 'linear');
                                    }, 300);
                                } else {
                                    this.calcClicked = false;
                                    $('.js-calc-offer-container').slideUp(200, 'linear');
                                    $('.js-filter__card-container').slideUp(200, 'linear');
                                }
                            }
                        });
                    }
                }
            }
        },
        rateInfo: function(rate) {
            if(rate.period_type == 'd') {
                if(rate.period > 1) {
                    return rate.period+' Days';
                } else {
                    return rate.period+' Day';
                }
            }

            if(rate.period_type == 'w') {
                if(rate.period > 1) {
                    return rate.period+' Weeks';
                } else {
                    return rate.period+' Week';
                }
            }

            if(rate.period_type == 'm') {
                if(rate.period > 1) {
                    return rate.period+' Months';
                } else {
                    return rate.period+' Month';
                }
            }

            if(rate.period_type == 'y') {
                if(rate.period > 1) {
                    return rate.period+' Years';
                } else {
                    return rate.period+' Year';
                }
            }
        },
        roundPrice: function(price) {
            if(price) {
                return price.toFixed(2);
            }
        },
        formatDate: function(date) {
            var d = new Date(date);
                month = this.monthNames[d.getMonth()];
                day = '' + d.getDate();
                year = d.getFullYear();
    
            if (day.length < 2) day = '0' + day;
    
            return [month+' '+day, year].join(', ');
        },
    },
    mounted() {
      setTimeout(() => {
        fnc.getRates();
      }, 200);

    }
});