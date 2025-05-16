const a=r=>(typeof r=="number"?r:Number(r)||0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g,"$&,"),t=r=>r?r<1024?r+" bytes":r<1048576?(r/1024).toFixed(1)+" KB":(r/1048576).toFixed(1)+" MB":"",l=(r,e=!1)=>{if(!r)return"";let n=r.replace(/\\r\\n|\\n|\\r/g,`
`);return n=n.replace(/\r\n|\r/g,`
`),n=n.replace(/\n{3,}/g,`

`),e?n.replace(/\n/g,"<br>"):n},i=r=>r?r.includes("_")?r.split("_").map(e=>e.charAt(0).toUpperCase()+e.slice(1).toLowerCase()).join(" "):r.replace(/([A-Z])/g," $1").replace(/^./,e=>e.toUpperCase()).trim():"";export{t as a,i as b,a as f,l as s};
