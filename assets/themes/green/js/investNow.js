
$(document).ready(function (){
    $(document).on('click', '.investNow', function () {
        var propertyInvestModal = new bootstrap.Modal(document.getElementById('investNowModal'))
        propertyInvestModal.show();

        let dataRoute = $(this).data('route');
        let property = $(this).data('property');
        let isAvailableFunding = property.is_available_funding;
        let dataProperty = property.title;
        let returnTimes = property.how_many_times;
        let dataInvestType = property.is_invest_type;
        let dataInvest = property.investmentAmount;
        let dataMinimumAmount = parseFloat(property.minimum_amount);
        let dataProfit = property.profit;
        let dataProfitType = property.profit_type;
        let dataPeriod = property.managetime.time;
        let dataPeriodType = property.managetime.time_type;
        let isInstallment = property.is_installment;
        let totalInstallments = property.total_installments;
        let installmentAmount = property.installment_amount;
        let installmentDuration = property.installment_duration + ' ' + property.installment_duration_type;
        let installmentLateFee = property.installment_late_fee;

        let dataFixedAmount = parseFloat(property.fixed_amount);
        let availableFunding = parseFloat(property.available_funding);

        var expired = $(this).data('expired');
        let symbol = $(this).data('symbol');
        let currency = $(this).data('currency');

        $('.show-currency').text(currency);

        $('.invest_now_modal').attr('action', dataRoute);
        $('.property-title').text(`Property: ${dataProperty}`);
        $("#investmentDuration").html(expired);

        if (isAvailableFunding == 1){
            $('.available_funding').text(`${symbol}${formatNumber(availableFunding)}`);
        }else{
            $('#available_funding').addClass('d-none');
        }



        $('.data_profit').text(`${(dataProfitType == '1') ? `${formatNumber(dataProfit)}%` : `${symbol}${formatNumber(dataProfit)}`}`);


        if (returnTimes == null) {
            let data = dataPeriod + ' ' + dataPeriodType;
            $('.profit_return').text(data);
            $('.data_return').text(data);
            $('.data_return').text(`${data} (Lifetime)`);
            $('.profit_return_message').text(`N.B: From the date on which the property investment period ends, you will get the profit for a lifetime consecutively ${data} after each.`)

        } else {
            let data = dataPeriod + ' ' + dataPeriodType;
            $('.data_return').text(`${data} (${returnTimes} times)`);

            $('.profit_return_message').text(`N.B: From the date on which the property investment period ends, you will get the profit for consecutively ${data} after each. And you will get a profit return maximum of ${returnTimes} Times.`)
        }


        if (dataFixedAmount > availableFunding && availableFunding > 0) {
            console.log('a');
            $('.data_invest').text(`${symbol}${formatNumber(availableFunding)}`);
            $('.totalInstallment').addClass('d-none');
            $('.installmentDuration').addClass('d-none');
            $('.installmentLateFee').addClass('d-none');

            if (dataInvestType == 0) {
                $('.invest-amount').val(formatNumber(dataMinimumAmount));
                $('#amount').attr('readonly', false);
            } else {
                $('.invest-amount').val(formatNumber(availableFunding));
                $('#amount').attr('readonly', true);
            }

        } else {
            console.log('b');
            $('.data_invest').text(dataInvest);
            if (isInstallment == 1) {
                $('.totalInstallment').removeClass('d-none');
                $('.total_installment').text(totalInstallments);
                $('.installmentDuration').removeClass('d-none');
                $('.installment_duration').text(installmentDuration);
                $('.installmentLateFee').removeClass('d-none');
                $('.installment_late_fee').text(`${symbol}${formatNumber(installmentLateFee)}`);
                $('.payInstallment').removeClass('d-none');
                $('.set_installment_amount').val(formatNumber(installmentAmount));
                $('.set_fixedInvest_amount').val(dataFixedAmount);
            } else {
                $('.totalInstallment').addClass('d-none');
                $('.installmentDuration').addClass('d-none');
                $('.installmentLateFee').addClass('d-none');
                $('.payInstallment').addClass('d-none');
            }

            if (dataInvestType == 0) {
                $('.invest-amount').val('');
                $('#amount').attr('readonly', false);
            } else {
                $('.invest-amount').val(formatNumber(dataFixedAmount));
                $('#amount').attr('readonly', true);
            }
        }

        if (availableFunding < dataMinimumAmount && availableFunding != 0) {
            $('.available_funding').text(`${symbol}${formatNumber(dataMinimumAmount)}`);
            $('.data_invest').text(`${symbol}${formatNumber(dataMinimumAmount)}`);
            $('.invest-amount').val(formatNumber(dataMinimumAmount));
            $('#amount').attr('readonly', true);
        }

        if (availableFunding == 0 && isAvailableFunding == 1) {
            $('.investHeading').text(__('Investment Completed'));
            $('.investModalSubmitBtn').addClass('d-none');
            $('.investModalPaymentForm').addClass('d-none');
        } else {
            $('.investHeading').text('Invest Now');
            $('.investModalSubmitBtn').removeClass('d-none');
            $('.investModalPaymentForm').removeClass('d-none');
        }
    });
});

function formatNumber(num) {
    return parseFloat(num.toString()); // Converts to a number again, removing unnecessary zeros
}

$(document).on('click', '#pay_installment', function () {
    if ($(this).prop("checked") == true) {
        $(this).val(1);
        let installmentAmount = $('.set_installment_amount').val();
        $('.invest-amount').val(formatNumber(installmentAmount));
        $('#amount').attr('readonly', true);
    } else {
        let fixedAmount = $('.set_fixedInvest_amount').val();
        $('.invest-amount').val(formatNumber(fixedAmount));
        $('#amount').attr('readonly', true);
        $(this).val(0);
    }

});
$(document).on('click', '.close_invest_modal', function () {
    $('#available_funding').removeClass('d-none');
    if ($('#pay_installment').prop("checked") == true) {
        $("#pay_installment").prop("checked", false);
    }
});

