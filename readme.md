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
            [week] => 1
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
    [info] => Array //评教信息
        (
            [0] => Array
                (
                    [param] => Array
                        (
                            [0] => 0000000047
                            [1] => 20011014
                            [2] => 袁一民
                            [3] => 学生评教
                            [4] => 新媒体概念及新媒体艺术实践
                            [5] => 101708020
                        )

                    [status] => 1 //是否已评教：1：已评，0：未评
                )
            ······
     [page] => Array //页码总数
        (
            [page] => 1
        )

```

#### 专有方法：根据页码获取信息
```
方法：getEvaluate($page=1)
返回：同默认方法
```