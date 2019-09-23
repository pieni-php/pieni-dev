Actual request params:
<pre><?php var_export($data['request']['params']); ?></pre>

Expected request param patterns:
<pre><?php var_export($data['config']['request']['param_patterns'][$data['request']['type']][$data['request']['actor']][$data['request']['target']][$data['request']['action']]); ?></pre>
