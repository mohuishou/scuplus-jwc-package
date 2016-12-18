# 四川大学教务处一些常用页面url

[toc]

## 登录
```
'http://202.115.47.141/loginAction.do?zjh='.$uid.'&mm='.$password
```

## 成绩

### 全部及格成绩
```
http://202.115.47.141/gradeLnAllAction.do?type=ln&oper=qbinfo&lnxndm
```

### 不及格成绩
```
http://202.115.47.141/gradeLnAllAction.do?type=ln&oper=bjg
```

### 本学期成绩
```
http://202.115.47.141/bxqcjcxAction.do
```

## 课程表

### 本学期课表
```
http://202.115.47.141/xkAction.do?actionType=6
```

## 个人信息

### 学籍信息
```
http://202.115.47.141/xjInfoAction.do?oper=xjxx
```

## 课程查询

### 本学期课程查询
```php
http://202.115.47.141/courseSearchAction.do?
params:
[
'kch' => '', //课程号
'kcm' => '', //课程名
'jsm' => '', //教师
 'xsjc' => '', //开课系所（学院）全称：电子信息学院
'skxq' => '', //上课星期 1-7
'skjc' => '', //上课节次 1-12
'xaqh' => '', //校区 01:望江 02:华西 03:江安
'jxlh' => '', //教学楼
'jash' => '', //教室
'pageSize' => $pageSize,
'pageNumber'=>$page,
'actionType' => 1,
]
//要展示的列
&showColumn=kkxsjc%23%BF%AA%BF%CE%CF%B5&showColumn=kch%23%BF%CE%B3%CC%BA%C5&showColumn=kcm%23%BF%CE%B3%CC%C3%FB&showColumn=kxh%23%BF%CE%D0%F2%BA%C5&showColumn=xf%23%D1%A7%B7%D6&showColumn=kslxmc%23%BF%BC%CA%D4%C0%E0%D0%CD&showColumn=skjs%23%BD%CC%CA%A6&showColumn=zcsm%23%D6%DC%B4%CE&showColumn=skxq%23%D0%C7%C6%DA&showColumn=skjc%23%BD%DA%B4%CE&showColumn=xqm%23%D0%A3%C7%F8&showColumn=jxlm%23%BD%CC%D1%A7%C2%A5&showColumn=jasm%23%BD%CC%CA%D2&showColumn=bkskrl%23%BF%CE%C8%DD%C1%BF&showColumn=xss%23%D1%A7%C9%FA%CA%FD&showColumn=xkxzsm%23%D1%A1%BF%CE%CF%DE%D6%C6%CB%B5%C3%F7
```

## 考试信息

### 考试安排

```
http://202.115.47.141/ksApCxAction.do?oper=getKsapXx
```

## 评教

### 表单页面
```
wjbm:0000000051  //评教id
bpr:20022167 //教师id
pgnr:602013030 //课程id
oper:wjShow
wjmc:%D1%A7%C9%FA%C6%C0%BD%CC //评教类型
bprm:%D1%CF%B1%F3%D3%EE //教师名字
pgnrm:%BC%C6%CB%E3%BB%FA%CD%A8%D0%C5%D3%EB%CD%F8%C2%E7 //评教课程名字
pageSize:20
page:1
currentPage:1
pageNo:
3D420CF:5F9B2704E286C34E4618AB477AC7DD57
```