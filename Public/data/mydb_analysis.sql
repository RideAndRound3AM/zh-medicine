******************************
库名 [medicine]

	1.患者表
	  [t_patient]
		id   姓名   身份证号   年龄   性别   患者卡号
	*	id   name    idcard     age    sex    card
	#医生开订单处方 收款工作人员 取药时需要用到
**************用户******************

	1.用户表
	  [t_user]
		id    姓名    密码         部门
	*	id    name  password	department

	2.角色表
	  [t_role]
		id   角色
	*	id   role

	3.用户角色中间表	(要根据用户查看职位)
	  [t_user_role]
		id   用户id   角色id （用户、职位，两外键）
	*   id    uid      rid  

	4.权限表
	  [t_right]
		id       权限名称  权限内容 
	*	id          name   rights

	5.角色权限中间表	(要根据职位查看权限)
	  [t_role_right]
		id  角色id  权限id （职位、权限，两外键）
	*	id   rid     rigid

	#限定医院工作人员的权限
******药品*******经验方**********处方****************

	1.药品表
	  [t_medicine]
		id   名称   产地     规格      分类    售价       默认用量    默认途径(途径指的是用法)  药库库存    药库高储 药库低储  药房库存 药房高储 药房低储
	*	id   name   area   standard    sort   sellprice     dosage	      way                    warehouse    whigh     wlow    pharmacy   phigh   plow
	#不同产地的同一种药品分开记录 
	#缴费划价时直接使用药品售价计算
	#默认用量 默认途径在医生开处方时需要使用
	#药库库存 和药房库存 以及对应的高低储可以直接设置在该用品中，每次直接对药品表进行操作

	1.功效表
	  [t_efficacy]
		id   功效名	(要个根据功效或病症查看经验方)
	*	id    name

	2.病症表
	  [t_illness]
		id   病症名
	*   id    name

	3.经验方表
	  [t_experience]
		id  处方名  功效id   病症id （功效、病症，两外键。两个一对多）
	*   id  name     eid       iid  
	#经验方有相对应的名字，医生检索经验方可以使用分组或处方名

	4.药品经验方中间表	(要根据经验方查看药品)
	  [t_medicine_experience]
		id   经验方id      药品id  单方剂量  药品途径   药品备注
	*	id     expid        medid    dosage     way      remarks


	5.订单表
	  [t_indent]
		id   时间     患者id        开方医生id        订单价格   收费人员id     缴费时间  配药人员id(订单状态根据人员ID是否为空确定)
	*	id   ctime   patientid     doctorid(uid)       iprice    tollid(uid)    tolltime  takeid(uid)
	#订单收费人员用于留作依据，订单的状态有未交费 已缴费 已拿药三种，根据订单表中的收费人员和配药人员是否为空确定
	6.处方表
	  [t_prescribe]
		id     诊断      订单id   贴数
	*	id   diagnosis     iid    num
	#一个订单对应多个处方，每个处方有相应的贴数
	7.药品处方中间表
	  [t_medicine_prescribe]
		id   处方id     药品id    单方剂量   药品途径   药品备注     价格
		id    pid       medid      dosage      way       remarks   sellprice
	#这儿的价格就是直接从药品表中查看的，故不需要单独标出，单方剂量和途径以及备注在医生开方时可以修改，不一定是默认，故需要单独给出
	#处方需留作历史记录，故需要价格列
********************供货商***********************
	
	1.供货商表
	  [t_merchant]
		id   供货商名称  开户银行    银行账号
	*	id     name      bankname     bankid
	#记录供货商的基本信息

	2.供货商药品中间表	
	  [t_medicine_merchant]
		id 供货商id   药品id 
	*	id  merid      medid
	#这儿如果是需要了解供货商有哪些药品则需要这张表，如果已知供货商有医院需要的药品，
	#采购时直接从供货商表中选择对应供货商，则可以不需要这张中间表

			*****申领****采购********
	1.部门申领单
	  [t_need]
		id   时间        部门     申领人id  状态(已出货，未出货)  驳回理由 
	*	id  ctime    department     uid      status(enum)          reason
	#部门申领单须由部门领导人审批，初始状态为空，通过后的状态为未使用，被采购计划表使用后状态为已使用

	2.部门申领药品中间表
		[t_medicine_need]
		id   部门申领单id   药品id     数量    
	*	id     nid           medid      num		

	3.采购计划表	(后面需要看到采购的状态，进行后续操作)
	  [t_buy]
		id 计划时间 状态(通过，未通过，未审批) 计划编辑用户id 审批用户id
	*	id   ctime     status                      wid(uid)       cid(uid)
	#采购计划表通过过后，商家才会相应的发货，故采购药品是否供货可从入库单中自动判断

	4.采购药品中间表	(采购药品供货商中间表)
	  [t_medicine_buy] 
		id    采购表id    药品id    采购数量     供货商id   是否供货(是，否 。当入库单中存在该药品时，触发器自动触发为是) 
	*	id      bid       medid       buynum       merid        status(enum) 

	********************库房*******************
	1.入库单
	  [t_inwarehouse]
		id  时间  采购单id  发票号码   检测入库药品用户id
	*	id  ctime    bid      invoice      uid
	#此处的采购单id可以使入库单和采购单进行联表，查看具体药品的具体供货情况
	#一个图库单对应的是一个发票号码

	2.入库单药品中间表
	  [t_medicine_inwarehouse]
		id  药品id    进价                              实际数量     供货商id    批次号 
	*	id  medid    buyprice    realitynum      merid     batch
	#入库药品实际数量直接加到药品表对应药品的药库数量上
	#每种药品对应的是供货商提供的批次号
	#？？？？这儿的供货商id对应的就是采购表中的供货商id，还需要么？
	***********************药库退货*********************
	
	#此处的退货表指的是已经存入药库的药品的退货
	1.退货药品表
	  [t_salesreturn]
		id   药品id    退货数量      供货商id    入库药品批次号  时间    退票号 
	*	id   medid     returnnum      merid         batch       ctime    invoice

	****************药库药品出库********************
	1.药库出库表
	  [t_outwarehouse]
		id    时间   出库用户id      部门       申领人id(对应部门申领表中的申领人id)  申领单id 
	*	id    ctime    outid(uid)    department        inid(uid)						nid
	#这儿是操作药品表中药库和药房的对应药品数量
	#出库药品中出现申领表中的药品则改变申领表的状态

	2.出库药品中间表
	  [t_medicine_outwarehouse]
		id    出库表id   药品id      药品数量
	*	id      owid       medid        num

	******************药库药品报废*****************
	#这儿没有单独使用一张表记录报废日期是因为日期可以直接写进对应得的报废用品当中

	1.报废药品表
	  [t_scrap]
		id     药品id        药品数量     报废日期  报废处理人id
	*	id      medid          num          ctime      uid
	#可以根据报废日期查出当次报废的药品及数量，报废过后减少相应的药库库存

	********************药房表*********************
	
	1.药品调价记录
	  [t_adjustprice]
		id   时间     药品id   调价用户id    之前售价      调价后售价 
	*	id   ctime     medid      uid       beforeprice     afterprice
	#记录调价具体信息的表，可用于财务计算等，调价对应改变药品表中药品的售价

	**************药房 药品高低储设置*************************
	#因为针对的都是某种药品，包括药库，药房的药品数量也在药品表中，故直接设置在药品表中

	****************药房药品申领*******************
	#此处药房药品申领就是上方的部门药品申领单










