(function(){

    $(function() {

        initExcludesCategories();


        $('#evaluation-form .nav-tabs').on('click', 'li.tab-items > a.tab-items-toggler', function(event) {
            event.stopPropagation();
            event.preventDefault();
            var $newTab = $(this).parent();

            if(!$newTab.hasClass('active'))
                switchTab($newTab);

            return false;
        });

        $('#evaluation-form .nav-tabs').on('click', 'li > a.modal-trigger:not(.delete)', function(event) {
            event.stopPropagation();
            event.preventDefault();

            var UrlContent = $(this).attr('href');

            showEvaluationTabForm(UrlContent);

            return false;
        });

        $('#evaluation-tab-form').on('click', '.btn.submit', function(event) {

            var $me = $(this),
                rel = $me.data('rel'),
                $rel= $('#' + rel);

            $rel.submit();

        });

        $('#evaluation-tab-form').on('submit', '#tab-form', function(event) {
            event.stopPropagation();
            event.preventDefault();

            var $me         = $(this),
                ActionUrl   = $me.attr('action'),
                EditMode    = $me.attr('data-editing'),
                Method      = (EditMode != 1) ? 'POST' : 'PUT';

            $.ajax({
                type: Method,
                url: ActionUrl,
                data: $me.serialize(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('.evaluation-panel').data('csrf')
                },
            }).done(function(response) {

                if(!response.success) {
                    showErrorBox($me, response.message);
                } else {
                    if(!response.editing){
                        createTab(response.data);
                    } else {
                        updateTab(response.data);
                    }

                    $('#evaluation-tab-form').modal('hide');
                }

            }).fail(function(response) {

            });

            return false;
        });

        $('.tab-content').on('click', 'a.add-category', function(event) {
            event.stopPropagation();
            event.preventDefault();

            /*var url = $(this).attr('href'),
                rel = $(this).data('rel'),
                store = $(this).data('store'),
                $select = $('#' + rel),
                $store = $('#' + store),
                values = $select.val();*/
            var rel = $(this).data('rel'),
                form= $('#' + rel);

            form.submit();

            // addQuestions(values, $select, $store);
            return false;
        });

        $('.tab-content').on('submit', '.category-form', function(event) {
            event.stopPropagation();
            event.preventDefault();

            var $store = $('.evaluation-panel'),
                isReport = parseInt($(this).data('report')) == 1,
                stepid = isReport ? $(this).find('input[name=evaluation_report_id]').val() : $(this).find('input[name=evaluation_step_id]').val(),
                $select= $(this).find('select'),
                excludes = $store.data('excludes'),
                parentid = $(this).data('rel'),
                panel = '<div class="panel panel-default category-panel" data-question=":dataquestions" data-category=":id">' +
                            '<div class="panel-heading" role="tab" id=":headcategoryid">' +
                                '<h4 class="panel-title">' +
                                    '<a role="button" data-toggle="collapse" data-parent="#:parentid" href="#:categoryid" aria-expanded="true" aria-controls=":categoryid">:title :w31ghtinfo</a>' +
                                '</h4>' +
                                '<div class="btn-group tab-controls" role="group">' +
                                    // '<a href=":questionsapi" data-step="'+stepid+'" type="button" class="btn btn-primary add-question" data-id=":id"><i class="fa fa-plus"></i>&nbsp;Add Question</a>' +
                                    '<a href=":deletecategoryapi" data-report="' +((isReport) ? "1" : "0")+ '" data-weight=":weight" data-step="'+stepid+'" type="button" class="btn btn-danger remove-category" data-id=":id"><i class="fa fa-remove"></i>&nbsp;Remove Category</a>' +
                                '</div>' +
                            '</div>' +
                            '<div id=":categoryid" class="panel-collapse collapse in" role="tabpanel" aria-labelledby=":headcategoryid">' +
                                '<div class="panel-body">' +
                                    '<ul class="list-group">:questions</ul>' +
                                '</div>' +
                            '</div>' +
                        '</div>';

            $.ajax({
                type: 'POST',
                url: $store.data('url'),
                data: $(this).serialize(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN' : $store.data('csrf')
                },
            }).done(function(data) {

                var items = data.data,
                    success = data.success,
                    totalWeight = parseFloat($('#content-tab-0').data('totalweight'));

                if(!success)
                    return false;

                for(var i in items)
                {
                    var cat = items[i],
                        id = cat.id,
                        questions = cat.questions,
                        categoryid = 'category-' + cat.id,
                        headcategoryid = categoryid + '-head',
                        title = cat.title,
                        questitems = '',
                        questid = [],
                        deletecategoryapi = data.delete_category_api,
                        questionsapi = data.questions_api,
                        weightinfo = "("+cat.weight+"%)";

                    excludes.push(cat.id);
                    totalWeight += cat.weight;
                    for(var j in questions)
                    {
                        var question = questions[j],
                            deletequestionapi = data.delete_question_api.replace(/(:questionid)/g, question.id);
                        questid.push(parseInt(question.id));
                        questitems += '<li class="list-group-item" id="question-'+question.id+'"><a href="'+deletequestionapi+'" class="btn btn-danger delete-question"  data-report="' +((isReport) ? "1" : "0")+ '" data-rel="question-'+question.id+'" data-id="'+question.id+'" data-step="'+stepid+'"><i class="fa fa-trash-o"></i></a> ' + question.title + '</li>';
                    }

                    var compiledPanel = panel.replace(':parentid', parentid).replace(/(:headcategoryid)/g, headcategoryid).replace(':dataquestions', JSON.stringify(questid)).replace(/(:parentid)/g, parentid).replace(/(:categoryid)/g, categoryid)
                        .replace(':questions', questitems).replace(':title', title).replace(/(:deletecategoryapi)/g, deletecategoryapi.replace(/(:id)/g, id)).replace(/(:questionsapi)/g, questionsapi).replace(/(:id)/g, id)
                        .replace(/(:weight)/g, cat.weight);

                    if(isReport) {
                        compiledPanel = compiledPanel.replace(':w31ghtinfo', weightinfo);
                    } else {
                        compiledPanel = compiledPanel.replace(':w31ghtinfo', '&nbsp;');
                    }

                    $('#' + parentid).append($(compiledPanel));
                }

                if(isReport) {
                    $('#content-tab-0').data('totalweight', totalWeight);
                    $('#content-tab-0').find('#total-weight-info').html(totalWeight+ " %");
                }
                console.log(excludes);
                $store.data('excludes', excludes);
                initQuestionCategoriesCombo($select, $select.data('url'));
            });

            return false;
        });

        $('.tab-content').on('click', 'a.delete-question', function(event) {
            event.stopPropagation();
            event.preventDefault();

            var qid = $(this).data('id'),
                rel = $(this).data('rel'),
                box = $(this).parents('.category-panel')[0],
                qd  = $(box).data('question'),
                isReport = parseInt($(this).data('report')) == 1,
                stepid = $(this).data('step'),
                params = isReport ? {report_id: stepid} : {step_id: stepid};

            $.ajax({
                type: 'DELETE',
                url: $(this).attr('href'),
                data: params,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('.evaluation-panel').data('csrf')
                }
            }).done(function(data) {
                if(data.success) {
                    $('#' + rel).remove();
                    var idx = qd.indexOf(qid),
                        nqd = qd.splice(idx, 1);

                    if(qd.length < 1)
                        window.location.href = '';

                    $(box).data('question', qd);
                }
            });

            return false;
        });

        $('.tab-content').on('click', 'a.remove-category', function(event) {
            event.stopPropagation();
            event.preventDefault();

            var id = $(this).data('id'),
                isReport = parseInt($(this).data('report')) == 1,
                stepid = $(this).data('step'),
                target = $(this).parents('.category-panel')[0],
                $target= $(target),
                $store = $('.evaluation-panel'),
                exclude= $store.data('excludes'),
                $select= isReport ? $('#content-tab-0').find('select') : $('#content-tab-' + stepid).find('select'),
                params = isReport ? {report_id: stepid} : {step_id: stepid},
                weight = parseFloat($(this).data('weight')),
                totalWeight = parseFloat($('#content-tab-0').data('totalweight'));
            $.ajax({
                type: 'DELETE',
                url: $(this).attr('href'),
                data: params,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('.evaluation-panel').data('csrf')
                }
            }).done(function(data) {
                if(data.success) {
                    $target.remove();
                    var idx = exclude.indexOf(id),
                        nqd = exclude.splice(idx, 1);

                    $store.data('excludes', exclude);
                    initQuestionCategoriesCombo($select, $select.data('url'));

                    $('#content-tab-0').data('totalweight', (totalWeight - weight));
                    $('#content-tab-0').find('#total-weight-info').html((totalWeight - weight) +" %");

                    window.location.href = '';
                }
            });

            return false;
        });

        $('.tab-content').on('click', 'a.add-question', function(event) {
            event.stopPropagation();
            event.preventDefault();

            var url     = $(this).attr('href'),
                catid   = $(this).data('id'),
                step    = $(this).data('step'),
                panel   = $(this).parents('.category-panel')[0],
                $panel  = $(panel)
                excludes= $panel.data('question'),
                $tbody  = $('#question-form table > tbody'),
                row     = '<tr>' +
                    '<td><input type="checkbox" name="question_id[]" value=":id" /></td>' +
                    '<td>:title</td>' +
                '</tr>';

            $('#question-form').data('step', step);
            $('#question-form').data('catid', step);

            $.ajax({
                type: 'GET',
                url : url,
                data: {
                    category_id: catid,
                    excludes: excludes
                }
            }).done(function(data) {
                $tbody.html('');

                $.each(data, function(index, value) {
                    $tbody.append(row.replace(/(:id)/g, value.id).replace(/(:title)/g, value.title));
                });

                if(data.length < 1)
                    $tbody.append('<tr><td colspan="2" style="text-align: center; font-style: italic">All questions have been used!</td></tr>');

                $('#questions-modal').modal('show');
            });



            return false;
        });

        $('#questions-modal').on('click', 'a.btn-submit', function(event){
            event.stopPropagation();
            event.preventDefault();

            var rel = $(this).data('rel'),
                form= $('#' + rel);

            form.submit();

            return false;
        });

        $('#evaluation-tab-form').on('change', '#vendor_assessment', function(event) {

            var isChecked = $(this).prop('checked');

            $('#vendor-number').hide();
            $('#vendors_number').val(1);

            if(isChecked)
                $('#vendor-number').show();

        });

    });

    function initExcludesCategories()
    {
        var $store = $('.evaluation-panel'),
            excludes = $store.data('excludes'),
            $categories = $('.category-panel');

        if($categories.length > 0) {

            $categories.each(function(idx, elm) {

                var $panel  = $(elm),
                    id      = parseInt($panel.data('category'));

                excludes.push(id);

            });

            $store.data('excludes', excludes);
        }

        initDefaultActiveTab();

    }

    function switchTab($newTab)
    {
        var $oldTab = $newTab.siblings('.active'),
            rel     = $newTab.data('contentid'),
            $relBody= $('#' + rel);

        $oldTab.removeClass('active');
        $newTab.addClass('active');

        switchContent($relBody)

    }

    function switchContent($newContent)
    {
        var $oldBody= $newContent.siblings('.show'),
            contentLoaded = $newContent.data('contentloaded'),
            excludes= $newContent.data('excludes'),
            $questionCategory = $newContent.find('select.select2-combo'),
            comboUrl = $questionCategory.data('url'),
            idCombo = $questionCategory.attr('id');

        $oldBody.removeClass('show');
        $newContent.addClass('show');

        if('evaluators' != idCombo){
            initQuestionCategoriesCombo($questionCategory, comboUrl);
        } else {
            $questionCategory.select2();
            $questionCategory.show();
        }
    }

    function showEvaluationTabForm(urlContent)
    {
        var $modal = $('#evaluation-tab-form'),
            $container = $modal.find('.modal-dialog');

        $.ajax({
            type: 'GET',
            url: urlContent,
            dataType: 'html'
        }).done(function(response) {
            $container.html(response);
            $('.select2-combo').select2();
            $modal.modal('show');
        });
    }

    function showErrorBox($form, msg)
    {
        $form.find('.alert.alert-danger').html(msg).show();
    }

    function createTab(data)
    {
        var tabElm = '<li role="presentation" class="tab-items" id=":tabid" data-contentid=":contenttabid">' +
            '<a href="#" class="tab-items-toggler">:tabname</a>' +
            '<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></a>'+
            '<ul class="dropdown-menu">'+
                '<li><a href=":tabmoveurl">Move Steps Left</a></li>'+
                '<li><a href=":tabediturl" class="modal-trigger">Edit</a></li>'+
                // '<li><a href=":tabdeleteurl" class="modal-trigger delete">Delete</a></li>'+
                '<li>'+
                '<form action=":tabdeleteurl" method="POST" class="form-inline" style="display: inline">' +
                    '<input type="hidden" name="_token" value="'+$('.evaluation-panel').data('csrf')+'" />' +
                    '<input type="hidden" name="_method" value="DELETE" />' +
                    '<button type="submit" class="hidden"></button>' +
                    '<a href="#" data-confirm="Are you sure?" class="js-delete-confirm">Delete</a>' +
                '</form>' +
                '</li>'+
            '</ul>'+
            '</li>',
            tabContentElm = '<div class="tab-contents" id=":contenttabid">'+
                '<div class="form-group">' +
                    '<label for="category_id">Question Category</label>' +
                    '<div class="row">' +
                        '<div class="col-md-6">' +
                            '<form method="POST" class="form category-form" data-report="0" id="question-category-:tabid" data-rel="categories-:contenttabid">' +
                            '<input type="hidden" name="evaluation_step_id" value=":id" />' +
                            '<select name="question_category[]" class="select2-combo" id="question-category-:tabid" data-url="'+data.question_category_api+'" multiple>' +
                            '</select>' +
                            '</form>' +
                        '</div>' +
                        '<div class="col-md-5">' +
                            '<a href="" class="btn btn-default add-category" data-rel="question-category-:tabid"><i class="fa fa-plus"></i>&nbsp;Add selected question categories</a>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="line line-dashed line-lg pull-in"></div>' +
                '<div class="panel-group" id="categories-:contenttabid">' +
                '</div>' +
            '</div>',
            id = data.id,
            tabid = 'tab-' + data.id,
            contenttabid = 'content-'+tabid,
            tabname = data.title,
            tabmoveurl = data.url.moveLeft,
            tabediturl = data.url.edit,
            tabdeleteurl = data.url.delete,
            compiledTabElm = tabElm.replace(/(:tabid)/g, tabid).replace(/(:contenttabid)/g, contenttabid).replace(':tabname', tabname).replace(':tabmoveurl', tabmoveurl).replace(':tabediturl', tabediturl).replace(':tabdeleteurl', tabdeleteurl),
            compiledTabContentElm = tabContentElm.replace(/(:contenttabid)/g, contenttabid).replace(/(:tabid)/g, tabid).replace(/(:id)/g, id);

        $('#evaluation-form .nav-tabs #last-tab').before($(compiledTabElm));
        $('.tab-content').append($(compiledTabContentElm));
        switchTab($('#' + tabid));
    }

    function updateTab(data)
    {
        var $CurrentTab = $('li#tab-' + data.id),
            $trigger    = $CurrentTab.find('a.tab-items-toggler');

        $trigger.html(data.title);
        switchTab($CurrentTab);
    }

    function initQuestionCategoriesCombo($combo, url)
    {

        var excludes = $('.evaluation-panel').data('excludes'),
            isReport = parseInt($combo.data('report')) == 1;

        $combo.html('');
        $.ajax({
            type: 'get',
            url: url,
            data: {
                excludes: JSON.stringify(excludes)
            },
            dataType: 'json'
        }).done(function(items) {

            var data = [];

            for(var i in items)
            {
                var item = items[i];

                $combo.append('<option value="'+item.id+'">'+item.title + ((isReport) ? ' ('+item.weight+'%)' : '') +'</option>');
            }

            $combo.select2();
            $combo.show();
        });
    }

    function initDefaultActiveTab()
    {
        var $ActiveTab = $('#last-tab');
        switchTab($ActiveTab);

    }

})();
