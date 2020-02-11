(function() {
    tinymce.PluginManager.add('pantherius_shortcode_button', function( editor, url ) {
	var menubuttons = [], menubuttons_answers = [];
	var newmenu = {} , newmenu_answers = {};
	if ( pantherius_shortcode_button.datas != "" ) {
	jQuery.each(pantherius_shortcode_button.datas, function( objindex, objvalue ) {
        newmenu = {
                    text: objvalue.name,
                    value: objvalue.id,
                    onclick: function() {
					editor.windowManager.open( {
							width : 500, 
							height : 300,
							title: pantherius_shortcode_button.languages[ 0 ],
							name: 'modalsurvey',
							text: objvalue.id,
							body: [
							{
								type: 'listbox',
								name: 'mode',
								label: pantherius_shortcode_button.languages[ 1 ],
								'values': [
									{text: pantherius_shortcode_button.languages[ 2 ], value: 'modal'},
									{text: pantherius_shortcode_button.languages[ 3 ], value: 'flat'}
								]
							},
							{
								type: 'listbox',
								name: 'visible',
								label: pantherius_shortcode_button.languages[ 4 ],
								'values': [
									{text: pantherius_shortcode_button.languages[ 5 ], value: 'false'},
									{text: pantherius_shortcode_button.languages[ 6 ], value: 'true'}
								]
							},
							{
								type: 'listbox',
								name: 'width',
								label: pantherius_shortcode_button.languages[ 7 ],
								'values': [
									{text: '100%', value: '100%'},
									{text: '90%', value: '90%'},
									{text: '80%', value: '80%'},
									{text: '70%', value: '70%'},
									{text: '60%', value: '60%'},
									{text: '50%', value: '50%'},
									{text: '40%', value: '40%'},
									{text: '30%', value: '30%'},
									{text: '20%', value: '20%'},
									{text: '10%', value: '10%'}
								]
							},
							{
								type: 'listbox',
								name: 'align',
								label: pantherius_shortcode_button.languages[ 8 ],
								'values': [
									{text: pantherius_shortcode_button.languages[ 9 ], value: 'center'},
									{text: pantherius_shortcode_button.languages[ 10 ], value: 'left'},
									{text: pantherius_shortcode_button.languages[ 11 ], value: 'right'}
								]
							},
							{
								type: 'listbox',
								name: 'textalign',
								label: pantherius_shortcode_button.languages[ 12 ],
								'values': [
									{text: pantherius_shortcode_button.languages[ 9 ], value: 'center'},
									{text: pantherius_shortcode_button.languages[ 10 ], value: 'left'},
									{text: pantherius_shortcode_button.languages[ 11 ], value: 'right'}
								]
							},
							{
								type: 'textbox',
								name: 'message',
								label: pantherius_shortcode_button.languages[ 13 ],
								value: pantherius_shortcode_button.languages[ 14 ]
							}
							],
							onsubmit: function( e ) {
								editor.insertContent( '[' + this.name() + ' id="' + this.text() + '" style="' + e.data.mode + '" align="' + e.data.align + '" textalign="' + e.data.textalign + '" width="' + e.data.width + '" visible="' + e.data.visible + '" message="' + e.data.message + '"]');
							}
						});
						}
                }
        newmenu_answers = {
                    text: objvalue.name,
                    value: objvalue.id,
                    onclick: function() {
					editor.windowManager.open( {
							width : 750, 
							height : 400,
							title: pantherius_shortcode_button.languages[ 15 ],
							name: 'survey_answers',
							text: objvalue.id,
							body: [
							{
								type: 'listbox',
								name: 'style',
								label: pantherius_shortcode_button.languages[ 1 ],
								'values': [
									{text: pantherius_shortcode_button.languages[ 16 ], value: 'progressbar'},
									{text: pantherius_shortcode_button.languages[ 17 ], value: 'linebar'},
									{text: pantherius_shortcode_button.languages[ 18 ], value: 'piechart'},
									{text: pantherius_shortcode_button.languages[ 19 ], value: 'barchart'},
									{text: pantherius_shortcode_button.languages[ 20 ], value: 'doughnutchart'},
									{text: pantherius_shortcode_button.languages[ 21 ], value: 'linechart'},
									{text: pantherius_shortcode_button.languages[ 22 ], value: 'polarchart'},
									{text: pantherius_shortcode_button.languages[ 23 ], value: 'radarchart'},
									{text: pantherius_shortcode_button.languages[ 24 ], value: 'plain'}
								]
							},
							{
								type: 'listbox',
								name: 'data',
								label: pantherius_shortcode_button.languages[ 25 ],
								'values': [
									{text: pantherius_shortcode_button.languages[ 26 ], value: 'full'},
									{text: pantherius_shortcode_button.languages[ 27 ], value: 'question'},
									{text: pantherius_shortcode_button.languages[ 28 ], value: 'answer'},
									{text: pantherius_shortcode_button.languages[ 29 ], value: 'answer_count'},
									{text: pantherius_shortcode_button.languages[ 30 ], value: 'answer_percentage'}
								]
							},
							{
								type: 'textbox',
								name: 'qid',
								label: pantherius_shortcode_button.languages[ 31 ]
							},
							{
								type: 'textbox',
								name: 'aid',
								label: pantherius_shortcode_button.languages[ 32 ]
							},
							{
								type: 'textbox',
								name: 'bgcolor',
								label: pantherius_shortcode_button.languages[ 33 ],
								value: '#5BC0DE'
							},
							{
								type: 'textbox',
								name: 'color',
								label: pantherius_shortcode_button.languages[ 34 ],
								value: '#FFFFFF'
							},
							{
								type: 'listbox',
								name: 'hidecounter',
								label: pantherius_shortcode_button.languages[ 35 ],
								'values': [
									{text: pantherius_shortcode_button.languages[ 36 ], value: 'no'},
									{text: pantherius_shortcode_button.languages[ 37 ], value: 'yes'}
								]
							},
							{
								type: 'listbox',
								name: 'hidequestion',
								label: pantherius_shortcode_button.languages[ 38 ],
								'values': [
									{text: pantherius_shortcode_button.languages[ 36 ], value: 'no'},
									{text: pantherius_shortcode_button.languages[ 37 ], value: 'yes'}
								]
							}],
							onsubmit: function( e ) {
							if (e.data.style=="plain"&&e.data.data=="full") {e.data.data = 'question'; if (e.data.qid=='') e.data.qid='1';}
								editor.insertContent( '[' + this.name() + ' id="' + this.text() + '" style="' + e.data.style + '" data="' + e.data.data + '" qid="' + e.data.qid + '" aid="' + e.data.aid + '" bgcolor="' + e.data.bgcolor + '" color="' + e.data.color + '" hidecounter="' + e.data.hidecounter + '" hidequestion="' + e.data.hidequestion + '"]');
							}
						});
						}
                }
				menubuttons.push(newmenu);
				menubuttons_answers.push(newmenu_answers);
 		});
      editor.addButton( 'pantherius_shortcode_button', {
            icon: 'icon dashicons-groups',
			title: pantherius_shortcode_button.languages[ 0 ],
			type: 'menubutton',
			menu: [
			{
                    text: pantherius_shortcode_button.languages[ 39 ],
                    value: 'survey',
                    onclick: function(e) {
						e.stopPropagation();
                    },
				menu: menubuttons
				},
			{
                    text: pantherius_shortcode_button.languages[ 40 ],
                    value: 'survey_answers',
                    onclick: function(e) {
						e.stopPropagation();
                    },
				menu: menubuttons_answers
				},
				
           ]
		   });
	}
    });
})();