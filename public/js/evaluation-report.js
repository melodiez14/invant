(
    function()
    {
        $(function() {
            initSelect2ReportCombo();
            $('select[select2=autoload]').select2();
            $('#change-qt-btn').on('click', function(event) {
                event.stopPropagation();
                event.preventDefault();

                if($('select[select2=autoload]').prop('disabled'))
                    $('select[select2=autoload]').removeAttr('disabled');

                $('#cancel-qt-btn').show();
                $('#submit-qt-btn').show();
                $(this).hide();

                return false;
            });

            $('#cancel-qt-btn').on('click', function(event) {
                event.stopPropagation();
                event.preventDefault();

                if(!$('select[select2=autoload]').prop('disabled'))
                    $('select[select2=autoload]').attr('disabled', 'disabled');

                $('#change-qt-btn').show();
                $('#submit-qt-btn').hide();
                $(this).hide();

                return false;
            });

            $('.btn-submit').on('click', function(event) {
                event.stopPropagation();
                event.preventDefault();

                var formId  = $(this).data('rel'),
                    form    = $('#' + formId);

                form.submit();

                return false;
            })
        });
        function initSelect2ReportCombo()
        {
            var $Select2Report  = $('.select2-report-combo'),
                dataUrl         = $Select2Report.data('url'),
                dataEvaluation  = $Select2Report.data('evaluation');

            $Select2Report.html(null);

            $.ajax({
                method: 'GET',
                url: dataUrl,
                data: {
                    evaluation: dataEvaluation
                },
                dataType: 'json'
            }).done(function(response) {
                $.each(response, function(idx, data)
                {
                    var option = '<option value="' +data.id+ '"' + ((data.evaluation_reports.length > 0) ? ' selected' : null) + '>' + data.profile.name + '</option>';
                    $Select2Report.append(option);
                });

                $Select2Report.select2();
            });
        }
    }

)();
