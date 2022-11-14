<script>
    $("#program-select").select2({
        placeholder:'Select program',
        dropdownParent:$("#SetProgramSubCategoryModal"),
        width:'100%'
    })
    $("#program-category-select").select2({
        placeholder:'Select program',
        dropdownParent:$("#SetProgramModal"),
        width:'100%'
    })
    $("#add-program-category-select").select2({
        placeholder:'Select program',
        dropdownParent:$("#AddCategoryModal"),
        width:'100%'
    })
    $(document).ready(function(){
        $("#load-table").DataTable({
            responsive:true,
            ajax:"{{ route('pim-show') }}",
            columns:[
                {
                    data:'category',
                    title:'Category'
                },
                {
                    data:'sub_categories',
                    title:'Sub Categories',
                    render:function(data,type,row){
                        
                        let output = `
                            <ul>
                                ${
                                    data.map((item)=>(`
                                        <li>${item.sub_category}</li>
                                    `))
                                }                                    
                            </ul>
                        `
                        return output;
                    }
                },
                {
                    data:'program_categories',
                    title:'Program Registered',
                    render:function(data,type,row){
                        let output = '';
                        if(row.sub_categories.length ==  0 ){
                            output = `
                                <ul>
                                    ${
                                        data.map((item)=>(`
                                            <li>${item.title} ${item.shortname} <a href="#" class="text-danger remove-category-program-btn" data-category-name="${row.category}" data-id="${item.program_item_category_id}" >Remove</a></li>
                                        `))
                                    }                                    
                                </ul>
                            `
                        }
                     
                        return output;
                    }
                },
                {
                    data:'fertilizer_category_id',
                    render: function(data,type,row){
                        return(`
                            <button class="btn btn-outline-info open-set-program-modal-btn"  data-category-id="${row.fertilizer_category_id}" data-sub-categ-count="${row.sub_categories.length}" data-category-name='${row.category}' data-toggle="modal" data-target="#SetProgramModal" type="button">
                                Set Program
                            </button>   
                        `);
                    }
                }
            ]
        })

        $("#load-table").on('click','.open-set-program-modal-btn',function(){
            let categoryName = $(this).data('category-name');
            let subCategCount = $(this).data('sub-categ-count');
            let categoryId = $(this).data('category-id');

            $("#category-name").text(categoryName);
            
            if(subCategCount > 0){                
                $(".load-registered-program-table-container").show();
                $(".save-btn-for-category").hide();
                $(".program-category-container").hide();                
                $('#load-registered-program-table').DataTable({
                    destroy:true,
                    responsive:true,
                    ajax:"{{ route('show-registered-program-sub-category',['fertilizer_category_id'=> ':fertilizer_category_id'])}}".replace(':fertilizer_category_id',categoryId),
                    columns:[
                        {
                            data:'sub_category',
                            title:'Sub Category',                            
                        },
                        {
                            data:'programs',
                            title:'Program Registered',
                        render:function(data,type,row){                                                                
                                let output = `
                                    <ul>
                                        ${
                                            data.map((item)=>(`
                                                <li>${item.title} ${item.shortname}  <a href="#" class="text-danger remove-sub-category-program-btn" data-sub-category-name="${row.sub_category}" data-id="${item.program_item_sub_category_id}" >Remove</a></li>
                                            `))
                                        }                                    
                                    </ul>
                                `
                                return output;
                            }
                        },
                        {
                            data:'fertilizer_category_id',
                            title:'Action',
                            render: function(data,type,row){
                                return(`
                                    <button class="btn btn-outline-info open-set-program-sub-categ-modal-btn" data-id='${row.program_item_sub_category_id}' data-category-id="${row.fertilizer_category_id}" data-sub-category-id="${row.fertilizer_sub_category_id}"  data-sub-category-name='${row.sub_category}' data-toggle="modal" data-target="#SetProgramSubCategoryModal" type="button">
                                        Set Program
                                    </button>   
                                `);
                            }
                        }
                    ]
                });
            }else{
                $("#category_id").val(categoryId);
                // filter program to category
                $.ajax({
                    url:"{{ route('filter-program-for-category',['fertilizer_category_id' => ':id']) }}".replace(':id',categoryId),                
                    success:function(response){                        
                        $("#program-category-select").find('option').remove();
                        $("#program-category-select").select2('val','');
                        if(response.status == true){
                    
                            response.data.map((item)=>{                            
                                console.warn(item)
                                $("#program-category-select").append(`
                                    <option value="${item.program_id}">${item.title}(${item.shortname})</option>
                                `);
                            })
                        
                        }
                        
                    }
                })
                $(".save-btn-for-category").show();
                $(".program-category-container").show();
                $(".load-registered-program-table-container").hide();
            }        
        });
        $("#load-registered-program-table").on('click','.open-set-program-sub-categ-modal-btn',function(){
            let subCategory = $(this).data('sub-category-name');
            let subCategoryId = $(this).data('sub-category-id');
            $("#sub_category_id").val(subCategoryId);
            $("#sub-category-name").text(subCategory);
            $.ajax({
                url:"{{ route('filter-program-for-sub-category',['fertilizer_sub_category_id' => ':id']) }}".replace(':id',subCategoryId),                
                success:function(response){
                    $("#program-select").find('option').remove();
                    $("#program-select").select2('val','');
                    if(response.status == true){
                  
                        response.data.map((item)=>{                            
                            console.warn(item)
                            $("#program-select").append(`
                                <option value="${item.program_id}">${item.title}(${item.shortname})</option>
                            `);
                        })
                       
                    }
                    
                }
            })
        });
         // ADD CATEGORY
         $("#AddCategoryForm").validate({
            rules:{
                category_name:{
                    required:true
                },
                program:{
                    required:true
                }
            },
            messages:{
                category_name:{
                    required:"<div class='text-danger'>Please input required field.</div>"
                },
                program:{
                    required:"<div class='text-danger'>Please input required field.</div>"
                }
            },
            submitHandler: function(){                
                $.ajax({
                    url:"{{ route('add-category') }}",
                    type:'post',
                    data:$("#AddCategoryForm").serialize(),
                    success: function(response){
                        if(response.status == true){
                            Swal.fire("Message",response.message,"success");
                            $("#AddCategoryModal").modal('toggle');
                            $("#load-table").DataTable().ajax.reload();
                        }else{
                            $("#AddCategoryModal").modal('toggle');
                            Swal.fire("Message",response.message,"error");
                        }
                    }
                })
            }                     
        });
        // ADD PROGRAM TO SUB CATEGORY
        $("#SetProgramSubCategoryForm").validate({
            rules:{
                program:{
                    required:true
                }
            },
            messages:{
                program:{
                    required:"<div class='text-danger'>Please input required field.</div>"
                }
            },
            submitHandler: function(){                
                $.ajax({
                    url:"{{ route('set-program-sub-category') }}",
                    type:'post',
                    data:$("#SetProgramSubCategoryForm").serialize(),
                    success: function(response){
                        if(response.status == true){
                            Swal.fire("Message",response.message,"success");
                            $("#SetProgramSubCategoryModal").modal('toggle');
                            $("#load-registered-program-table").DataTable().ajax.reload();
                        }else{
                            $("#SetProgramSubCategoryModal").modal('toggle');
                            Swal.fire("Message",response.message,"error");
                        }
                    }
                })
            }                     
        });

        // ADD PROGRAM TO CATEGORY
        $("#SetProgramCategoryForm").validate({
            rules:{
                program:{
                    required:true
                }
            },
            messages:{
                program:{
                    required:"<div class='text-danger'>Please input required field.</div>"
                }
            },
            submitHandler: function(){                
                $.ajax({
                    url:"{{ route('set-program-category') }}",
                    type:'post',
                    data:$("#SetProgramCategoryForm").serialize(),
                    success: function(response){
                        if(response.status == true){
                            Swal.fire("Message",response.message,"success");
                            $("#SetProgramModal").modal('toggle');
                            $("#load-table").DataTable().ajax.reload();
                        }else{
                            $("#SetProgramModal").modal('toggle');
                            Swal.fire("Message",response.message,"error");
                        }
                    }
                })
            }                     
        });
        $(document).on('click',".remove-sub-category-program-btn",function(){
            let subCategoryProgramId = $(this).data('id');            
            let subCategoryName = $(this).data('sub-category-name');
            Swal.fire({
                    title: 'Are you sure',
                    text: `Do you want to remove ${subCategoryName}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Remove',
                    allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            let payload= {
                                _token:"{{ csrf_token() }}",
                                program_item_sub_category_id: subCategoryProgramId
                            }
                            $.ajax({
                                    url:"{{ route('remove-program-sub-category') }}",
                                    type:'post',
                                    data:payload,
                                    success: function(response){
                                        if(response.status == true){
                                            Swal.fire("Message",response.message,"success");                                            
                                            $("#load-registered-program-table").DataTable().ajax.reload();
                                        }else{
                                            $("#SetProgramSubCategoryModal").modal('toggle');
                                            Swal.fire("Message",response.message,"error");
                                        }
                                    }
                                }) 
                        }else{

                        }
                    });
        });
        $(document).on('click',".remove-category-program-btn",function(){
            let categoryProgramId = $(this).data('id');            
            let categoryName = $(this).data('category-name');
            Swal.fire({
                    title: 'Are you sure',
                    text: `Do you want to remove ${categoryName}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Remove',
                    allowOutsideClick: false
                    }).then((result) => {
                        if(result.isConfirmed){
                            let payload= {
                                _token:"{{ csrf_token() }}",
                                program_item_category_id: categoryProgramId
                            }
                            $.ajax({
                                    url:"{{ route('remove-program-category') }}",
                                    type:'post',
                                    data:payload,
                                    success: function(response){
                                        if(response.status == true){
                                            Swal.fire("Message",response.message,"success");                                            
                                            $("#load-table").DataTable().ajax.reload();
                                        }else{
                                            $("#SetProgramSubCategoryModal").modal('toggle');
                                            Swal.fire("Message",response.message,"error");
                                        }
                                    }
                                }) 
                        }else{

                        }
                    });
        });
        
    });
</script>