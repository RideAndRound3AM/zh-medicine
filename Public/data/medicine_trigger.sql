use medicine;
#添加角色
insert into t_role(role)
	values("医生处方开具"),#开经验方，处方
		("订单收费"),#订单收银，订单退费
		("处方配药"),#订单配药
		("药房药品管理"),#药房药品申领
		("药库药品管理"),#验收入库，制作采购单
		("药库报废退货");#审批采购单,药库退货，药品报废
#添加角色做测试	
insert into t_patient(name,idcard,age,sex,card)
	values("赵香炉","510722199302044875",24,"男","12345678"),
		  ("李万基","511321199254681254",12,"男","87654321");

#添加用户
insert into t_user(name,password,department)
	values("chenyisheng","12345678","医生办公室"),
		  ("root","12345678","all"),
		  ("yangshouqian","12345678","缴费处"),
		  ("annayao","12345678","取药处"),
		  ("pengguanli","12345678","药房"),
		  ("zhongguanli","12345678","药库"),
		  ("wangzongguan","12345678","药库"),
		  ("zhangyuanzhang","12345678","院长办公室");

#添加用户角色中间表数据
insert into t_user_role(uid,rid)
	values(1,1),
		  (2,1),
		  (3,2),
		  (4,3),
		  (5,4),
		  (6,5),
		  (7,6),
		  (8,1),
		  (8,2),
		  (8,3),
		  (8,4),
		  (8,5),
		  (8,6);

#添加药品做测试
insert into t_medicine(name,area,standard,sort,sellprice)
	values("川贝","四川","g","化痰这颗平喘药",10),
		  ("人参","长白山","g","补虚药",100),
		  ("白术","四川","g","补虚药",20),
		  ("茯苓","云南","g","利水渗湿药",15),
		  ("甘草","四川","g","补虚药",5);
#添加分组做测试
insert into t_efficacy(name)
	values("祛暑利湿"),
	      ("清暑益气"),
	      ("补益剂");
insert into t_illness(name)
	values("脑残"),
		  ("肾虚");
#添加经验方做测试
insert into t_experience(name,eid,iid)
	values("四君子汤",3,1);
insert into t_medicine_experience(expid,medid,dosage,way,remarks)
	values(1,2,10,"煎服","100年份"),
		  (1,3,9,"煎服",""),
		  (1,4,9,"煎服",""),
		  (1,5,6,"煎服","炙甘草");

#添加供货商做测试
insert into t_merchant(name,bankname,bankid)
	values("哈药集团","工商银行","455215"),
		  ("老王药业","中国农业银行","5414774");
insert into t_medicine_merchant(merid,medid)
	values(1,1),
		  (2,2),
		  (1,3),
		  (1,4),
		  (2,5);
#添加权限
insert into t_right(name,rights)
	values('处方开单','Prescribe/index'),
		  ('经验方设置','Experience/index'),
		  ('药品基本数据维护','Medicine/getMedicine_pharmacy'),
		  ('药品供货商维护','Merchant/getMerchant'),
		  ('采购计划','Buy/showBuy'),
		  ('采购入库','Inwarehouse/showInwarehouse'),
		  ('药库药品退还供货公司','Salesreturn/getSalesreturn'),
		  ('药库药品出库','Outwarehouse/showStgOutWhousePage'),
		  ('药库药品盘点','default'),
		  ('药品默认用法设置','Medicine/getMedicine_doctor'),
		  ('药房药品入库确认','default'),
		  ('药品调价','Medicine/getMedicine_sellprice'),
		  ('药房药品高低储设置','Medicine/getPharmacy_res'),
		  ('药房药品申领','Need/showDrawMedicine'),
		  ('药房药品药品退还药库','Outwarehouse/showMedBackStgPage'),
		  ('药房药品配药','Indent/showAllTakePage'),
		  ('药房药品发药','Indent/showSeedMedicinePage'),
		  ('药房药品退药','default'),
		  ('药品盘点','default'),
		  ('药房库存药品信息查询','default'),
  		  ('药库库存药品信息查询','default'),
  		  ('中药处方查询','default'),
  		  ('订单缴费','Indent/showTollPage'),
  		  ('药库药品高低储设置','Medicine/getWarehouse_res'),
  		  ('采购单审批','Buy/showExamineBuy'),
		  ('药库药品报废','Scrap/showScrap');
  		
  		  
#添加角色权限中间表数据
insert into t_role_right(rid,rigid)
	values(1,1),
	      (1,2),
	      (5,3),
	      (6,4),
	      (5,5),
	      (5,6),
	      (6,7),
	      (6,8),
	      (6,9),
	      (4,10),
	      (4,11),
	      (6,12),
	      (4,13),
	      (4,14),
	      (4,15),
	      (3,16),
	      (3,17),
	      (3,18),
	      (6,19),
	      (2,23),
	      (5,24),
	      (6,25),
	      (6,26);






#药库出库表 部门申领表 触发器
drop trigger if exists insert_outwarehouse;
delimiter |
create trigger insert_outwarehouse after insert on t_outwarehouse
for each row
begin
	declare inum int;#用于存储未出货药品的数量
	select count(id) into inum from t_medicine_need where id=new.nid and status='未出货';
	if inum=0 then
		update t_need set status="已出货" where id=new.nid;
	end if;
end  |
delimiter ;


#退货药品 库存 触发器
drop trigger if exists insert_salesreturn;
delimiter |
create trigger insert_salesreturn after insert on t_salesreturn
for each row
begin 
	declare wnum int default 0;#用于存储查询出的药库药品库存
	select warehouse into wnum from t_medicine where id=new.medid;
	update t_medicine set warehouse=(wnum-new.returnnum) where id=new.medid;
end |
delimiter ;



#药库药品出库 库存 触发器
drop trigger if exists insert_medicine_outwarehouse;
delimiter |
create trigger insert_medicine_outwarehouse after insert on t_medicine_outwarehouse
for each row
begin 
	declare wnum int default 0;#用于存储查询出的药库药品库存
	declare pnum int default 0;#用于存储查询出的药房药品库存
	select warehouse,pharmacy into wnum,pnum from t_medicine where id=new.medid;
	update t_medicine set warehouse=(wnum-new.num),pharmacy=(pnum+new.num) where id=new.medid;
end |
delimiter ;

#药库药品报废 库存 触发器
drop trigger if exists insert_scrap;
delimiter |
create trigger insert_scrap after insert on t_scrap
for each row
begin 
	declare wnum int default 0;#用于存储查询出的药库药品库存
	select warehouse into wnum from t_medicine where id=new.medid;
	update t_medicine set warehouse=(wnum-new.num) where id=new.medid;
end |
delimiter ;


#入库单药品中间表 库存 触发器
drop trigger if exists insert_medicine_inwarehouse;
delimiter |
create trigger insert_medicine_inwarehouse after insert on t_medicine_inwarehouse
for each row
begin
	declare wnum int default 0;#用于存储查询出的药库药品库存
	select warehouse into wnum from t_medicine where id=new.medid;
	update t_medicine set warehouse=(wnum+new.realitynum) where id=new.medid;
end |
delimiter ;



