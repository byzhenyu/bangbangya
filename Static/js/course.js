

function initCourse( course_period, course_class, course_unit) {
    var $period = $('#period_id'),
        $courseClass = $('#class_id'),
        $courseUnit = $('#unit_id');

    initPeriod();

    function initPeriod() {
        $.post("/index.php/Core/CoursePeriod/getCoursePeriod", {}, function (data) {
            console.log(data);
            $period.html('<option data-id="0" value="">请选择学段</option>');
            for (var i = 0; i < data.length; i++) {
                $period.append('<option data-id="' + data[i].id
                    + '" value="' + (data[i].id) + '">'
                    + data[i].period_name + '</option>');
            }
            $period.val(course_period);
            initClass();
        });
    }

    function initClass() {
        var period_id = $period.find('option:selected').data('id');

        if (!parseInt(period_id)) {
            $courseClass.html('<option data-id="0" value="">请选择课程</option>');
            $courseClass.val(course_class);
            initUnit();
            return;
        }
        $.post("/index.php/Core/CoursePeriod/getCourseClass", {period_id: period_id}, function (data) {
            $courseClass.html('<option data-id="0" value="">请选择课程</option>');
            for (var i = 0; i < data.length; i++) {
                $courseClass.append('<option data-id="' + data[i].id
                    + '" value="' + (data[i].id) + '">'
                    + data[i].class_name + '</option>');
            }
            $courseClass.val(course_class);
            initUnit();
        });
    }

    function initUnit() {
        var class_id = $courseClass.find('option:selected').data('id');

        if (!parseInt(class_id)) {
            $courseUnit.html('<option data-id="0" value="">请选择单元</option>');
            $courseUnit.val(course_unit);
            return;
        }
        $.post("/index.php/Core/CoursePeriod/getCourseUnit", {class_id: class_id}, function (data) {
            $courseUnit.html('<option data-id="0" value="">请选择单元</option>');
            for (var i = 0; i < data.length; i++) {
                $courseUnit.append('<option data-id="' + data[i].id
                    + '" value="' + (data[i].id) + '">'
                    + data[i].unit + '</option>');
            }
            $courseClass.val(course_unit);
        });
    }

    $period.change(function () {
        course_class = '';
        course_unit = '';

        initClass();
    });

    $courseClass.change(function () {
        course_unit = '';

        initUnit();
    });

    $courseUnit.change(function () {
    });

}



