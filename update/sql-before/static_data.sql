
INSERT INTO `ext_user_preference` (`id_user`, `ext`, `preference`, `item`, `value`) VALUES
(474, 0, 'tab', 0, 'project'),
(474, 100, 'pwidget_projecttreefilter_filters', 0, 'title,status'),
(474, 100, 'pwidget_projecttree_filter_project', 0, '[{"filter":"title","value":"rewrite"}]'),
(474, 100, 'project', 0, '976'),
(474, 100, 'task_tab', 35564, 'timetracking'),
(474, 100, 'task_tab', 0, 'timetracking'),
(233, 1002, 'tab', 0, 'todos')
;


INSERT INTO `ext_filter_set` (`id`, `date_update`, `date_create`, `deleted`, `sorting`, `hidden`, `id_user`, `usergroups`, `type`, `title`) VALUES
(1, 0, 0, 0, 0, 0, 233, '', 'task', 'My new Tasks'),
(2, 0, 0, 0, 1, 0, 233, '', 'task', 'Project: todoyu'),
(3, 0, 0, 0, 2, 0, 233, '', 'task', 'Von mir zur Abnahme'),
(4, 0, 0, 0, 0, 0, 0, '', 'task', 'LLL:portal.tab.todos'),
(5, 0, 0, 0, 0, 0, 0, '', 'task', 'LLL:portal.tab.feedbacks')

;


INSERT INTO `ext_filter_condition` (`id`, `date_update`, `date_create`, `id_user_create`, `deleted`, `id_set`, `filter`, `value`, `negate`) VALUES
(1, 0, 0, 233, 0, 1, 'acknowledged', '0', 0),
(2, 0, 0, 233, 0, 1, 'userAssigned', '233', 0),
(3, 0, 0, 233, 0, 1, 'status', '2', 0),
(4, 0, 0, 233, 0, 2, 'project', '411', 0),
(5, 0, 0, 233, 0, 3, 'owner', '233', 0),
(6, 0, 0, 233, 0, 3, 'status', '4', 0)

;


INSERT INTO `ext_portal_tab` (`id`, `deleted`, `sorting`, `id_user`, `usergroups`, `id_filterset`, `class`, `title`) VALUES
(1, 0, 2, 0, '0', 4, 'tab_todos', 'LLL:portal.tab.todos'),
(2, 0, 1, 0, '0', 5, 'toptabicon', 'LLL:portal.tab.feedbacks'),
(3, 0, 3, 0, '0', 0, 'tab_appointments', 'LLL:portal.tab.appointments'),
(4, 0, 4, 0, '0', 0, 'tab', 'LLL:portal.tab.invoices')
;