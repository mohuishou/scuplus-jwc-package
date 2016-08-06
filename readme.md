# scuplus-jwc-package
> scuplus 四川大学教务处信息模拟获取

## 使用

待添加..

## 接口

### 成绩接口

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

专有方法：获取本学期成绩
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