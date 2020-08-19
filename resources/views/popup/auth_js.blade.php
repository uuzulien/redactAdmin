<script>
    if ($("#gid option:selected").val() > 0){
        changeUser(users, pdr);
    }
    // 部门选择
    $('#gid').change(function () {
        changeUser(users, pdr);
    });

    function changeUser(datas, pdr=0) {
        var group_id = $("#gid option:selected").val();
        var content = '<option value="0" >全部</option>';

        for (var key in datas) {
            var data = datas[key];
            if (group_id == data.gid){
                content += `<option value="${data.id}" ${pdr == data.id ? 'selected' : ''}>${data.name}</option>`;
            }
        }
        $('#pdr').html(content);
    }
</script>
