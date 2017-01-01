# scuplus-jwc-package
> scuplus 四川大学教务处信息模拟获取

## 安装
```php
composer require mohuishou/scuplus-jwc-package
```

## 使用

**注意：**
每一个类都包含一个默认方法Index(),用于一些常用的方法。有些类包含一些默认方法，在下面的接口都有相关的介绍

```php
require_once "vendor/autoload.php";
try {
    //所有的对象通过这个方法创建，api在下方有详细说明
    $g = \Mohuishou\Lib\ScuplusJwc::create('Grade', 学号, 密码);
    $grade = $g->index();
    print_r($grade);
}catch (\Exception $e){
    echo $e->getMessage();
}
```

## 接口

### 创建对象

#### 类：\Mohuishou\Lib\ScuplusJwc

#### 方法：静态方法 create
```php
方法：create($classname,$uid.$password)
参数：
    $classname: 类名
    可选值：
        Course（课程信息）、Grade（成绩）、Evaluate（评教）
        Exam（考试）、Schedule（课程表）、Student（学生个人信息）
    $uid 学号
    $password 密码
```


### 成绩接口（Grade）

#### 默认方法：获取所有成绩
```php
方法：index()
返回：
Array
(
    [0] => Array //学期
        (
            [0] => Array //课程
                (
                    [courseId] => 105366020
                    [lessonId] => 41
                    [name] => 大学英语（综合）-1
                    [enName] => College English (Comprehensive)-1
                    [credit] => 2
                    [courseType] => 必修
                    [grade] => 61.0 
                    [termId] => 1 //学期id 1表示大一上
                    [term] => 2014-2015学年秋(两学期)
                )
                ····
```

#### 专有方法：获取本学期成绩
```php
方法：getThisTermGrade()
返回：
Array
(
    [0] => Array
        (
            [courseId] => 101708020
            [lessonId] => 01
            [name] => 新媒体概念及新媒体艺术实践
            [enName] => The concept analysis and art practice of new media
            [credit] => 2
            [courseType] => 任选
            [grade] => 84
        )
    ····
```

### 课表（Schedule）

#### 默认方法：获取课程表
```php
方法：index()
返回：
Array
(
    [1] => Array
        (
            [plan] => 电子信息工程卓越班培养方案
            [courseId] => 205051030
            [name] => 软件工程
            [lessonId] => 02
            [credit] => 3.0
            [courseType] => 选修
            [examType] => 
            [teacher] => 赵成萍
            [studyWay] => 正常
            [chooseType] => 选中
            [allWeek] => 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17
            [day] => 3
            [session] => 10,11,12
            [campus] => 望江
            [building] => 基教楼C座
            [classroom] => C202
        )
    ······
```

### 考试安排（Exam）

#### 默认方法：获取考表

```php
方法:index()
返回：
Array
(
    [0] => Array
        (
            [exam_name] => 期中考试
            [campus] => 江安
            [building] => 一教A座
            [classroom] => A222
            [class_name] => 大学英语（创意阅读）-4-117 
            [week] => 10 //第几周：第十周
            [day] => 6 //周几：周六
            [date] => 2016-05-07
            [time] => 14:00-16:00
            [seat] =>  
        )

    [1] => Array
        (
            [exam_name] => 期末考试
            [campus] => 江安
            [building] => 综合楼C座
            [classroom] => C506
            [class_name] => 毛泽东思想和中国特色社会主义理论体系概论-59 
            [week] => 18
            [day] => 2
            [date] => 2016-06-28
            [time] => 16:30-18:00
            [seat] => 49
        )
    ······
```

### 个人信息（Student）

#### 默认方法：获取个人信息
```php
方法：index()
返回：
Array
(
    [name] => //姓名
    [en_name] => //英文名
    [id] => //身份证号
    [sex] => 男
    [type] => 本科
    [status] => 在校
    [nation] => 汉族
    [native] => 四川
    [birth] => //生日
    [political] => 共青团员
    [college] => 电子信息学院
    [major] => //专业
    [year] => 2014级
    [class] => 142050603 //班级代码
    [campus] => //校区
)
```

### 课程信息

#### 默认方法：返回前50条课程信息
```php
方法：index()
返回：
Array
(
    [0] => Array
        (
            [college] => 艺术学院
            [courseId] => 101003050
            [name] => 芭蕾舞基本功训练-1
            [lessonId] => 01
            [credit] => 5.0
            [examType] => 考试
            [teacher] => 海维清
            [allWeek] => 3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18
            [day] => 1
            [session] => 1,2
            [campus] => 江安
            [building] => 艺术大楼
            [classroom] => 形体4
            [max] => 25
            [studentNumber] => 0
            [courseLimit] => 允许系所专业艺术学院舞蹈表演允许系所专业艺术学院舞蹈学;
        )
    ······
```

#### 专有方法
1. 通过教师名字获取课程
```php
方法:getCourseByTeacher($teacher,$page=0,$pageSize=50)
返回：同默认方法
```
2. 通过课程名获取课程
```php
方法：getCourseByName($name,$page=0,$pageSize=50)
返回：同默认方法
```

3. 通过多个条件获取课程
```php
方法：getCourse($params=[],$page=0,$pageSize=50)
参数：$params
[
    'kch' => '', //课程号
    'kcm' => '', //课程名
    'jsm' => '', //教师
    'xsjc' => '', //开课系所（学院）全称：电子信息学院
    'skxq' => '', //上课星期 1-7
    'skjc' => '', //上课节次 1-12
    'xaqh' => '', //校区 01:望江 02:华西 03:江安
]
返回：同默认方法
```

### 评教信息(Evaluate)

#### 默认方法：获取前20条评教信息
```php
方法：index()
Array
(
    [info] => Array
        (
            [0] => Array
                (
                    [jwc_evaluate_id] => 0000000051
                    [jwc_teacher_id] => 20022167
                    [teacher_name] => 严斌宇
                    [teacher_type] => 1 //教师类型：1：教师，0：助教
                    [evaluate_type] => 学生评教
                    [course_name] => 计算机通信与网络
                    [course_id] => 602013030
                    [status] => 1
                )
            ……
        )
    [verify] => Array //验证信息，无关紧要
            (
                [value] => 1
                [name] => currentPage
            )
```

#### 专有方法：获取已经评教的信息
```php
方法：getEvaluateData($params)
参数：
$params=[
        'jwc_evaluate_id' => '0000000052',
        'jwc_teacher_id' => 'zj2015222055202',
        'teacher_name' => '钟飞',
        'teacher_type' => '0',
        'evaluate_type' => '研究生助教评价',
        'course_name' => '自控原理（全英文）',
        'course_id' => '602015030',
        'verify_name'=>'DA8C8756',
        'verify_value'=>'BF2173036C4163BA48DBB9EE1BB20529',
        'status'=>1 //评教状态，必须为1，为1时表示已经评教
    ];
返回：
Array
(
    [comment] => 钟飞助教很耐心的
    [star] => 5 //平均分
)
```

#### 专有方法：提交评教信息
```php
方法：evaluate($params);
参数：
    $params=[
        'jwc_evaluate_id' => '0000000052',
        'jwc_teacher_id' => 'zj2015222055202',
        'teacher_name' => '钟飞',
        'teacher_type' => '0',
        'evaluate_type' => '研究生助教评价',
        'course_name' => '自控原理（全英文）',
        'course_id' => '602015030',
        'star'=>5,
        'verify_name'=>'DA8C8756',
        'verify_value'=>'BF2173036C4163BA48DBB9EE1BB20529',
        'comment'=>'钟飞助教很耐心的',
        'status'=>1
    ];
返回：
Array
(
    [message] => 评估失败，请返回！ //重复评教也会失败，评教之前请先检查评教状态
    [status] => 0 //1表示成功
)
```