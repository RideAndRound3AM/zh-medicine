drop database if exists medicine;
create database medicine;
use medicine;
set names utf8;
#患者表
create table t_patient(
	id int primary key auto_increment,
	name varchar(32) not null comment '患者姓名',
	idcard varchar(32) not null comment '身份证号',
	age int not null comment '患者年龄',
	sex enum("男","女") comment '患者性别',
	card varchar(32) unique not null comment '患者卡号'
) default charset utf8;
#用户表
create table t_user(
	id int primary key auto_increment,
	name varchar(32) not null comment '用户姓名',
	password varchar(32) not null comment '用户密码',
	department varchar(32) not null comment '用户部门'
) default charset utf8;
#角色表
create table t_role(
	id int primary key auto_increment,
	role varchar(32) unique not null comment '角色名'
) default charset utf8;
#用户角色中间表
create table t_user_role(
	id int primary key auto_increment,
	uid int comment '用户id',
	rid int comment '角色id',
	foreign key fk1(uid) references t_user(id),
	foreign key fk2(rid) references t_role(id)
) default charset utf8;
#权限表
create table t_right(
	id int primary key auto_increment,
	name varchar(32) not null comment '权限名称',
	rights varchar(128) not null comment '权限内容'
) default charset utf8;
#角色权限中间表
create table t_role_right(
	id int primary key auto_increment,
	rid int comment '角色id',
	rigid int comment '权限id',
	foreign key fk1(rid) references t_role(id),
	foreign key fk2(rigid) references t_right(id)
) default charset utf8;
#药品表
create table t_medicine(
	id int primary key auto_increment,
	name varchar(32) unique not null comment '药品名称',
	area varchar(32) not null comment '药品产地',
	standard varchar(32) not null default 0 comment '药品规格',
	sort varchar(32) not null comment '药品分类',
	sellprice float(10,2)  not null default 0 comment '药品售价',
	dosage float(10,2) comment '默认用量',
	way varchar(32) comment '默认途径',
	warehouse float(10,2) not null default 0 comment '药库库存',
	whigh float(10,2) comment '药库高储',
	wlow float(10,2) comment '药库低储',
	pharmacy float(10,2) not null default 0 comment '药房库存',
	phigh float(10,2) comment '药房高储',
	plow float(10,2) comment '药房低储'
) default charset utf8;
#功效表
create table t_efficacy(
	id int primary key auto_increment,
	name varchar(32) unique not null comment '功效名称'
) default charset utf8;
#病症表
create table t_illness(
	id int primary key auto_increment,
	name varchar(32) unique not null comment '病症名称'
) default charset utf8;
#经验方表
create table t_experience(
	id int primary key auto_increment,
	name varchar(32) unique not null comment '经验方名称',
	eid int comment '功效id',
	iid int comment '病症id',
	foreign key fk1(eid) references t_efficacy(id),
	foreign key fk2(iid) references t_illness(id)
) default charset utf8;
#药品经验方中间表
create table t_medicine_experience(
	id int primary key auto_increment,
	expid int comment '经验方id',
	medid int comment '药品id',
	dosage float(10,2) not null comment '单方剂量',
	way varchar(32)not null comment '药品途径',
	remarks varchar(32) comment '药品备注',
	foreign key fk1(expid) references t_experience(id),
	foreign key fk2(medid) references t_medicine(id)
) default charset utf8;
#订单表
create table t_indent(
	id int primary key auto_increment,
	ctime datetime not null comment '开单时间',
	patientid int not null comment '患者id',
	doctorid int not null comment '开方医生id',
	iprice float(10,2) not null comment '订单价格',
	tollid int comment '收费人员id',
	tolltime datetime comment '缴费时间',
	takeid int comment '配药人员id',
	taketime datetime comment '取药时间',
	foreign key fk1(patientid) references t_patient(id),
	foreign key fk2(doctorid) references t_user(id),
	foreign key fk3(tollid) references t_user(id),
	foreign key fk4(takeid) references t_user(id)
) default charset utf8;
#处方表
create table t_prescribe(
	id int primary key auto_increment,
	diagnosis varchar(32) not null comment '医生诊断',
	iid int comment '订单id',
	num int not null comment '处方贴数',
	status enum('正常','撤销') default '正常',
	foreign key fk1(iid) references t_indent(id)
) default charset utf8;
#药品处方中间表
create table t_medicine_prescribe(
	id int primary key auto_increment,
	pid int comment '处方id',
	medid int comment '药品id',
	dosage float(10,2) not null comment '单方剂量',
	way varchar(32) not null comment '药品途径',
	remarks varchar(64) comment '药品备注',
	sellprice float(10,2) not null comment '药品价格',
	foreign key fk1(pid) references t_prescribe(id),
	foreign key fk2(medid) references t_medicine(id)
) default charset utf8;
#供货商表
create table t_merchant(
	id int primary key auto_increment,
	name varchar(32) unique not null comment '供货商名称',
	bankname varchar(32) not null comment '开户银行',
	bankid varchar(32) unique not null comment	'银行账号'
) default charset utf8;
#供货商药品中间表
create table t_medicine_merchant(
	id int primary key auto_increment,
	merid int comment '供货商id',
	medid int comment '药品id',
	foreign key fk1(merid) references t_merchant(id),
	foreign key fk2(medid) references t_medicine(id)
) default charset utf8;
#部门申领单
create table t_need(
	id int primary key auto_increment,
	ctime datetime not null comment '申领单创建时间',
	department varchar(32) not null comment '申领部门',
	uid int comment '申领人id',
	status enum("已出货","未出货") default '未出货' comment '申领单状态',
	reason varchar(32) comment '驳回理由',
	foreign key fk1(uid) references t_user(id)
) default charset utf8;
#部门申领药品中间表
create table t_medicine_need(
	id int primary key auto_increment,
	nid int comment '部门申领单id',
	medid int comment '药品id',
	status enum("已出货","未出货") default '未出货' comment '药品申领状态',
	num float(10,2) not null comment '申领药品数量',
	foreign key fk1(nid) references t_need(id),
	foreign key fk2(medid) references t_medicine(id)
) default charset utf8;
#采购计划表
create table t_buy(
	id int primary key auto_increment,
	ctime datetime not null comment '采购计划时间',
	status enum("通过","未通过","未审批") default '未审批' comment '采购计划表的状态',
	wid int comment '计划编辑用户',
	cid int comment '计划审批用户',
	foreign key fk1(wid) references t_user(id),
	foreign key fk2(cid) references t_user(id)
) default charset utf8;
#采购药品中间表
create table t_medicine_buy(
	id int primary key auto_increment,
	bid int comment '采购表id',
	medid int comment '药品id',
	buynum float(10,2) not null comment '采购数量',			
	merid int comment '供货商id',
	status enum("已供货","未供货") default '未供货' comment '供货状态',
	foreign key fk1(bid) references t_buy(id),
	foreign key fk2(medid) references t_medicine(id),
	foreign key fk3(merid) references t_merchant(id)
) default charset utf8;
#入库单
create table t_inwarehouse(
	id int primary key auto_increment,
	ctime datetime not null comment '入库时间',
	bid int comment '采购表id',
	invoice varchar(32) not null comment '发票号码',
	uid int comment '检测入库药品用户id',
	foreign key fk1(bid) references t_buy(id),
	foreign key fk2(uid) references t_user(id)
) default charset utf8;
#入库单药品中间表
create table t_medicine_inwarehouse(
	id int primary key auto_increment,
	medid int comment '药品id',
	inwid int comment '入库单id',
	buyprice float(10,2) not null comment '进价',
	realitynum float(10,2) not null comment '实际数量',
	merid int comment '供货商id',
	batch varchar(32) not null comment '批次号',
	foreign key fk1(medid) references t_medicine(id),
	foreign key fk2(merid) references t_merchant(id),
	foreign key fk3(inwid) references t_inwarehouse(id)
) default charset utf8;
#退货药品表
create table t_salesreturn(
	id int primary key auto_increment,
	medid int comment '药品id',
	returnnum float(10,2) not null comment '退货数量',
	merid int comment '供货商id',
	batch varchar(32) not null comment '入库药品批次号',
	ctime datetime not null comment '退货时间',
	invoice varchar(32) not null comment '退票号',
	foreign key fk1(medid) references t_medicine(id),
	foreign key fk2(merid) references t_merchant(id)
) default charset utf8;
#药库出库表
create table t_outwarehouse(
	id int primary key auto_increment,
	ctime datetime not null comment '出库时间',
	outid int comment '出库用户id',
	department varchar(32) not null comment '出库部门',
	inid int comment '申领人id',
	nid int comment '申领表id',
	foreign key fk1(outid) references t_user(id),
	foreign key fk2(inid) references t_user(id),
	foreign key kf3(nid) references t_need(id)
) default charset utf8;
#出库药品中间表
create table t_medicine_outwarehouse(
	id int primary key auto_increment,
	owid int comment '出库表id',
	medid int comment '药品id',
	num float(10,2) not null comment '药品数量',
	foreign key fk1(owid) references t_outwarehouse(id),
	foreign key fk2(medid) references t_medicine(id)
) default charset utf8;
#报废药品表
create table t_scrap(
	id int primary key auto_increment,
	medid int comment '药品id',
	num float(10,2) not null comment '药品数量',
	ctime datetime not null comment '报废时间',
	uid int comment '报废处理人id',
	foreign key fk1(medid) references t_medicine(id),
	foreign key fk2(uid) references t_user(id)
) default charset utf8;
#药品调价记录
create table t_adjustprice(
	id int primary key auto_increment,
	ctime datetime not null comment '调价时间',
	medid int comment '药品id', 
	uid int comment '调价用户id',
	beforeprice int not null comment '调价前售价',
	afterprice int not null comment '调价后售价',
	foreign key fk1(medid) references t_medicine(id),
	foreign key fk2(uid) references t_user(id)
) default charset utf8;
