Actual request:
<pre><?php var_export($data['request']); ?></pre>

Actual number of request params:
<pre><?php echo count($data['request']['params']); ?></pre>

Expected number of request params:
<pre><?php echo count($data['config']['request']['param_patterns'][$data['request']['type']][$data['request']['actor']][$data['request']['target']][$data['request']['action']]); ?></pre>
