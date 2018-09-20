<div class="layui-fluid">
	<table class="layui-table">
        <tbody>
        <tr>
            <th width="80"><b>标题</b></th>
            <td>
                {$info.title}
            </td>
        </tr>
        <tr>
            <th><b>执行地址</b></th>
            <td>
                <a href="{$info.log_url}">{$info.log_url}</a>
            </td>
        </tr>
        <tr>
            <th><b>执行者</b></th>
            <td>
                {$info.username}

            </td>
        </tr>
        <tr>
            <th><b>执行IP</b></th>
            <td>
                {$info.action_ip}
            </td>
        </tr>

        <tr>
            <th><b>执行时间</b></th>
            <td>
                {if condition="is_int($info['create_time'])"}
                {:date('Y-m-d H:i:s',$info['create_time'])}
                {else /}
                {$info['create_time']}
                {/if}
            </td>
        </tr>

        <tr>
            <th colspan="2" style="text-align: center;font-weight: bold;">日志详情</th>

        </tr>
        <tr>
            <td colspan="2">
                {$info.log}
            </td>

        </tr>
        </tbody>
   </table>
</div>