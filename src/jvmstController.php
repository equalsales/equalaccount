<?php

namespace Equal\Account;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
//use App\jvmst;
//use App\jvdet;
//use App\salebillmst;
//use App\partymst;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use DataTables;
use App\DataTables\SSP;
use Session;

class jvmstController extends Controller
{
    // function show()
    // {
    //     $data = jvmst::all();
    //     return view('jvmst.jvlist',["data"=> $data]);
    // }
    function show(Request $req)
    {
        $cno = Session::get('companyid');;
        $fromdate = Session::get('startdate');
        $todate = Session::get('enddate');

        if ( $req->ajax() )
		{
            $value = $req->input('search.value');
		    $data = DB::table('jvmst')
                    ->leftjoin('partymst as b', 'jvmst.crid', '=', 'b.id')
                    ->leftjoin('partymst as c', 'jvmst.drid', '=', 'c.id')
                     ->select('jvmst.id', 'jvmst.serial', 'jvmst.srchr',
                    "jvmst.date","jvmst.vchrtype",
                    'b.party as crac', 'c.party as drac','jvmst.usrid')
                    ->where('jvmst.cno', '=', $cno)
                    ->whereDate('jvmst.date', '>=',$fromdate)
                    ->whereDate('jvmst.date', '<=',$todate);  
                    if($value!='')
                    {     
                        $data->whereRaw(" ( jvmst.serial like  '%$value%' 
                                            or jvmst.srchr like '%$value%' 
                                            or jvmst.vchrtype like '%$value%' 
                                            or b.party like '%$value%'
                                            or c.party like '%$value%'
                                            or jvmst.id like  '%$value%' ) ");

                    }
                    $data->limit(100)            
                    ->get();                

		    return DataTables::of($data)
		    ->addColumn('action',function( $xdata){
		        $button = editdelbtn($xdata, 'editjv', 'deletejv','printjv','',[],"jvmst");
		    return $button;                
		})
		->rawColumns(['action'])
		->make(true);    
		}
		return view('jvmst::jvlist');        
    }
    function addjv()
    {
        $jvmst= new jvmst;
        $jvmst->id=0;
        $book = new partymst;
        $party = new partymst;
        $jvdet = new jvdet;
        $chqbank = new partymst;
        $jvdet->id=0;
        $jvdet->billid=0;
        $datadet = [ $jvdet ];
        $osdet = new salebillmst ;
        $osdet->id=0;
        $osdet->serial=0;
        $osdet = [ $osdet ];  
        $show = 0;
        return view('jvmst.addjv',["data"=> $jvmst,"datadet"=>$datadet,'book'=>$book,'party'=>$party,'chqbank'=>$chqbank,'osdet'=>$osdet,'show'=>$show]);
    }  
    function editjv($id)
    {
        
        $data = DB::table('jvmst as a')
            ->leftjoin('partymst as b', 'a.drid', '=', 'b.id')
            ->leftjoin('partymst as c', 'a.crid', '=', 'c.id')
            ->leftjoin('usermst', 'a.usrid', '=', 'usermst.id')
            ->select('b.party as drparty','c.party as crparty','usermst.username','a.*' )
            ->where('a.id', '=', $id)
            ->get();  
        
        // $datadet = jvdet::where('controlid', $data[0]->id)->get();
        $datadet = DB::select(DB::raw( " select  c.billserial,c.billsrchr,c.billdate,c.billamt,c.balance,c.billno,a.*  
        from jvdet a 
        left join (  

        select 'SALE' as module , serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , serial as  billno,id as billid  
        from salebillmst 

        union all

        select 'PURC' as module , serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from purcbillmst 

        union all

        select 'GREYPURC' as module, serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from greybillmst 

        union all

        select 'MILLGP' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from millgpmst 

        union all

        select 'JOBBILL' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from jobbillmst 

        union all

        select 'GENPUR' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from genpurcbillmst

        union all

        select 'PURC_RET' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from purcbillretmst

        union all

        select 'SALE_RET' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from salebillretmst

        union all

        select 'GENPUR_RET' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from genpurcbillretmst

        union all

        select 'RECEIPT' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , serial as billno,id as billid  
        from receiptmst    

        
        ) as c on c.billid = a.billid and c.module  = a.module
        where a.controlid = $id "));
        $osdet = new salebillmst ;
        $osdet->id=0;
        $osdet->serial=0;
        $osdet = [ $osdet ];
         //return $datadet;
         $show = 0;
        return view('jvmst.addjv',["data"=> $data[0],"datadet"=>$datadet,"osdet"=>$osdet,'show'=>$show]);
    }
    function createjvmst(Request $req)
    {
        $id = intval($req->id);
        $activity = '';
        if( intval($req->id)>0) 
        {
            //Edit Mode
            $activity = 'EDIT';
            $validator = Validator::make($req->all(), [
                'serial' => 'required|unique:jvmst,serial,'. $req->id
            ]);

            
            if (!$validator->passes()) {
                return response()->json(['success'=>false, 'error'=>$validator->errors()->all()]);
         
            }                        
            jvdet::where('controlid',$id)->delete();

            $jvmst = jvmst::find($id);                              

            // $this->updatejv($req->id, $req);
            // return response()->json(['success'=>true]);
        }
        else
        {
            //Add Mode
            
            $activity = 'ADD';
            
            $validator = Validator::make($req->all(), [
                'serial' => 'required|unique:jvmst'
            ]);
            if (!$validator->passes()) {
                return response()->json(['success'=>false, 'error'=>$validator->errors()->all()]);              
            }                        
            $jvmst= new jvmst;
        }
            // $jvmst= new jvmst;
            $jvmst->mode = getval( $req->mode, 'C' );
            $jvmst->modcode = getval( $req->modcode, 'C' );
            $jvmst->serial = getval( $req->serial, 'N' );
            $jvmst->srchr = getval( $req->srchr, 'C' );
            $jvmst->date = getval( $req->date, 'C' );
            $jvmst->drid = getval( $req->drid, 'N' );
            $jvmst->crid = getval( $req->crid, 'N' );
            $jvmst->netamt = getval( $req->netamt, 'N' );
            $jvmst->refper = getval( $req->refper, 'N' );
            $jvmst->refamt = getval( $req->refamt, 'N' );
            $jvmst->vchrtype = getval( $req->vchrtype, 'C' );
            $jvmst->remarks = getval( $req->remarks, 'C' );
            $jvmst->remarks2 = getval( $req->remarks2, 'C' );
            $jvmst->cno =  $req->session()->get('companyid'); 
            $jvmst->usrid = $req->session()->get('userid');      
            $jvmst->totadj = 0;
            $jvmst->billinfo = '';
            updatecreateddate($id,$jvmst,"jvmst");
            $jvmst->save();        

            createlog($req, $activity, $jvmst->id, $req->serial.' '.$req->srchr.' '.'JV Saved.!!','jvmst');       
            return response()->json(['success'=>true, 'id'=>$jvmst->id]); 

    }
    function createjvdet(Request $req)
    {
        $data = $req->jvdet;
        $id= $req->id;
        $last = $req->last;
        $billinfo ='';
        
        if( $data != null)
        {
            foreach ($data as $item) {
                $jvdet = new jvdet;
                if ( intval( $item['id'])>0)
                {
                    $jvdet->id  = $item['id'];
                }
                $billinfo  =  $billinfo.getval($item['billno'],'C').',';
                $jvdet->controlid = $id;
                $jvdet->srl = $item['srl'];
                $jvdet->module = getval($item['module'],'C');
                $jvdet->adjustamt = getval($item['adjustamt'],'N');
                $jvdet->billid = getval($item['billid'],'N');
                $jvdet->modcode = getval( $req->modcode, 'C' );
                $jvdet->oldentryid = 0;
                $jvdet->oldcontrolid = 0;
                $jvdet->cno = $req->session()->get('companyid');
                $jvdet->usrid = $req->session()->get('userid');  
                $jvdet->save();        
                            
            }   
            $updateqry =" update jvmst 
                        set  
                        billinfo = " . v2c($billinfo) . "
                        where id = " . $id;
            $updatedata = DB::update($updateqry);   
            if ($last) 
            {
            $updateqry =" update jvmst a
                        inner join (
                        select b.controlid,
                        sum(b.adjustamt) as totadj,
                        sum(b.discamt) as totdiscamt,
                        sum(b.discamt2) as totdiscamt2,
                        sum(b.discamt3) as totdiscamt3,
                        sum(b.addamt) as totaddamt,
                        sum(b.addamt2) as totaddamt2,
                        sum(b.intamt) as totintamt,
                        sum(b.expgramt) as totexpgramt
                        from jvdet as b
                        group by b.controlid
                        )  c on a.id = c.controlid
                        set  
                        a.totadj = c.totadj,
                        a.totdiscamt = c.totdiscamt,
                        a.totdiscamt2 = c.totdiscamt2,
                        a.totdiscamt3 = c.totdiscamt3,
                        a.totaddamt = c.totaddamt,
                        a.totaddamt2 = c.totaddamt2,
                        a.totintamt = c.totintamt,
                        a.totexpgramt = c.totexpgramt
                        where a.id = " . $id;
            $updatedata = DB::update($updateqry); 
            }           
        }


        return response()->json(['success'=>true]);

    }            
    public function updatejv($id, Request $req)
    {
        
        $jvmst = jvmst::find($id);
        $jvmst->mode = getval( $req->mode, 'C' );
        $jvmst->modcode = getval( $req->modcode, 'C' );
        $jvmst->serial = getval( $req->serial, 'N' );
        $jvmst->srchr = getval( $req->srchr, 'C' );
        $jvmst->date = getval( $req->date, 'C' );
        $jvmst->drid = getval( $req->drid, 'N' );
        $jvmst->crid = getval( $req->crid, 'N' );
        $jvmst->netamt = getval( $req->netamt, 'N' );
        $jvmst->refper = getval( $req->refper, 'N' );
        $jvmst->refamt = getval( $req->refamt, 'N' );
        $jvmst->vchrtype = getval( $req->vchrtype, 'C' );
        $jvmst->remarks = getval( $req->remarks, 'C' );
        $jvmst->cno =  $req->session()->get('companyid');   
        $jvmst->usrid = $req->session()->get('userid');    
        $jvmst->save();     
        
        //return $id;
        
        jvdet::where('controlid',$id)->delete();

        $data = $req->jvdet;
        //return $data;
        if( $data != null)
        {
            foreach ($data as $item) {
                    $jvdet = new jvdet;
                    $jvdet->controlid = $jvmst->id;
                    $jvdet->srl = $item['srl'];
                    $jvdet->module = getval($item['module'],'C');
                    // $jvdet->serial = getval($item['billserial'],'N');
                    // $jvdet->billno = getval($item['billno'],'N');
                    // $jvdet->billdate = getval($item['billdate'],'C');
                    // $jvdet->billamt = getval($item['billamt'],'N');
                    // $jvdet->balance = getval($item['balance'],'N');
                    $jvdet->adjustamt = getval($item['adjustamt'],'N');
                    $jvdet->billid = getval($item['billid'],'N');
                    $jvdet->modcode = getval( $req->modcode, 'C' );
                    $jvdet->oldentryid = 0;
                    $jvdet->oldcontrolid = 0;
                    $jvdet->cno = $req->session()->get('companyid');
                    $jvdet->usrid = $req->session()->get('userid');  
                    $jvdet->save(); 
            }  
        }        
    }
    function deletejv( $id, Request $req)
    {

        $data = jvmst::where('id',$id)->first();
        if( !$data )
        {
            $req->session()->flash('errorMsg','Record Not Found');
            return Redirect::to("jvlist")->withSuccess('Record Not Found'); 
        }
        jvdet::where('controlid',$id)->delete();
        jvmst::where('id',$id)->delete();
        $req->session()->flash('msg','Record has been delete');
        return 
        Redirect::to("jvlist")->withSuccess('Record Not Found'); 
    }
    function getjvserial(Request $req)
    {
        $cno = $req->session()->get('companyid');
        $serial = jvmst::where('cno','=',$cno)->max('serial');
        if ($serial== null)
        {
            $serial=1;
        }
        else
        {
            $serial= $serial + 1;
        }
        return response()->json(['success'=> true, 'serial'=>$serial]);
    }  

    function printJV(Request $req,$id)
    {   
        $cno = $req->session()->get('companyid');

        $fromserial = $req->fromserial;
        $toserial = $req->toserial;
        //return $id;

        $data = DB::table('jvmst as a')
            ->leftjoin('partymst as b', 'a.drid', '=', 'b.id')
            ->leftjoin('partymst as c', 'a.crid', '=', 'c.id')
            ->select(DB::raw("a.id, a.serial, a.srchr,
            date_format(a.date,'%d-%m-%Y') as date,
            b.party as drac, c.party as crac, a.remarks,b.addr1,b.addr2,
            b.gstregno,b.mobileno,b.phoneno,a.netamt as netamt,a.cheque,date_format(a.chqdate,'%d-%m-%Y') as chqdate,a.mode"));
            if($id>0)
            {
                $data = $data->where('a.id', '=', $id);
            }
            else
            {
                $data = $data->whereBetween('a.serial', [$fromserial, $toserial]);
            }
            $data = $data->get();

            //return $data;

        $companymst = DB::table('companymst as a')
                    ->leftjoin('citymst as b', 'a.cityid', '=', 'b.id')
                    ->leftjoin('statemst as c', 'a.stateid', '=', 'c.id')
                    ->leftjoin('countrymst as d', 'a.countryid', '=', 'd.id')
                    ->select('b.city', 'c.state', 'd.country',
                    'a.*')
                    ->where('a.id', '=', $cno)
                    ->get();
            
        $inw = convert_number_to_words($data[0]->netamt);
        
        //return $data;
        return view('jvmst.printjv',["dataall"=> $data,"companymst"=>$companymst[0],'inw'=>$inw, 'length'=>count($data)]);
    }
    function viewjv($id)
    {
        
        $data = DB::table('jvmst as a')
            ->leftjoin('partymst as b', 'a.drid', '=', 'b.id')
            ->leftjoin('partymst as c', 'a.crid', '=', 'c.id')
            ->leftjoin('usermst', 'a.usrid', '=', 'usermst.id')
            ->select('b.party as drparty','c.party as crparty','usermst.username','a.*' )
            ->where('a.id', '=', $id)
            ->get();  
        
        // $datadet = jvdet::where('controlid', $data[0]->id)->get();
        $datadet = DB::select(DB::raw( " select  c.billserial,c.billsrchr,c.billdate,c.billamt,c.balance,c.billno,a.*  
        from jvdet a 
        left join (  

        select 'SALE' as module , serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , serial as  billno,id as billid  
        from salebillmst 

        union all

        select 'PURC' as module , serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from purcbillmst 

        union all

        select 'GREYPURC' as module, serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from greybillmst 

        union all

        select 'MILLGP' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from millgpmst 

        union all

        select 'JOBBILL' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from jobbillmst 

        union all

        select 'GENPUR' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from genpurcbillmst

        union all

        select 'PURC_RET' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from purcbillretmst

        union all

        select 'SALE_RET' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from salebillretmst

        union all

        select 'GENPUR_RET' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , billno as  billno,id as billid  
        from genpurcbillretmst

        union all

        select 'RECEIPT' as module,serial as billserial , srchr as billsrchr ,date as billdate , netamt as billamt , netamt as balance , serial as billno,id as billid  
        from receiptmst    

        
        ) as c on c.billid = a.billid and c.module  = a.module
        where a.controlid = $id "));
        $osdet = new salebillmst ;
        $osdet->id=0;
        $osdet->serial=0;
        $osdet = [ $osdet ];
         //return $datadet;
         $show = 1;
        return view('jvmst.addjv',["data"=> $data[0],"datadet"=>$datadet,"osdet"=>$osdet,'show'=>$show]);
    }
}
