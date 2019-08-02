import React, 
	{Component} 
	from 'react';
import {Platform, 
	StyleSheet, 
	Text, 
	View, 
	AsyncStorage,
	Image,
	NetInfo,
	TouchableWithoutFeedback,
	TextInput,
	Picker,
	CheckBox,
	ScrollView} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';

/* -- Custom Components  -- */
import Constants from '../commons/Constants.js';

export default class OwnersLocation extends Component{

	state = {
		inputNumberOfPersons : '1 Person',
		inputNameOfDish      : '',
		inputDescription     : '',
		inputFoodType        : 'Vegetable',
		inputFoodPrice       : '',
    	submitted            : false
	}

	onChangeNumberOfPersons = (ItemValue,ItemIndex)=>{
		this.setState({inputNumberOfPersons:ItemValue});
	}

	onChangeFoodType = (ItemValue,ItemIndex)=>{
		this.setState({inputFoodType:ItemValue});
	}

	checkNameOfDishCharacters = ()=>{
		return this.state.inputNameOfDish.match(/^[A-z0-9]+$/);
	}

	checkPriceInputValiditiy = ()=>{
		const currentInputPrice = this.state.inputFoodPrice;
		if(Number.isInteger(Number(currentInputPrice)) == false)return false;
		else if(Number(currentInputPrice)<=0)return false;
		else if(Number(currentInputPrice)>0)return true;
		else return false;
	}

	submitMenuInfo = ()=>{
		if(this.state.submitted == true)return;
		else if(this.checkNameOfDishCharacters() == false){
			this.props.doSendAReportMessage('Please input a valid character for the name');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.state.inputNameOfDish.length == 0){
			this.props.doSendAReportMessage('Please input a valid name for the dish');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else if(this.checkPriceInputValiditiy()==false){
			this.props.doSendAReportMessage('Please input a valid price for the dish');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);
		}
		else{
			const data = {
				numberOfPersons : this.state.inputNumberOfPersons,
				dishDescription : this.state.inputDescription,
				name            : this.state.inputNameOfDish,
				foodType        : this.state.inputFoodType,
				price           : this.state.inputFoodPrice
			}
			this.setState({submitted:true});
			setTimeout(()=>{
				this.setState({submitted:false});
			},Constants.REPORT_DISPLAY_TIME+1000);
			this.props.doAddANewDish(data);
		}
	}

	render() {
	    return (
	    	<React.Fragment>
	    		<View style={{
	    				height:'100%',
	    				width:'100%',
	    				position:'relative',
	    				alignItems:'center'
	    		}}>
	    			<View style={{
	    					height: '8.5%',
	    					width:'100%',
	    					position:'relative',
						    borderColor: '#ddd',
						    borderBottomWidth: 0,
						    shadowColor: '#000',
						    shadowOffset: {
								width: 0,
								height: 5,
							},
							shadowOpacity: 0.34,
							shadowRadius: 6.27,
							elevation: 9,
						    backgroundColor: '#555dff',
						    flexDirection: 'row'
	    			}}>
	    				<TouchableWithoutFeedback
	    					onPress={()=>this.props.doSetHomePage(Constants.OWNER_ROLE_PAGES.RESTAURANT_INFO)}>
		    				<Text style={{
			    					height: '50%',
			    					width: '18%',
			    					position: 'relative',
			    					color: '#000',
			    					fontSize: 13,
			    					fontWeight: 'bold',
			    					textAlign: 'center',
			    					textAlignVertical: 'center',
			    					borderRadius: 100,
			    					borderWidth:2,
			    					left: '10%',
			    					top: '3%'
			    			}}>
			    				RETURN
			    			</Text>
			    		</TouchableWithoutFeedback>

	    				<Text style={{
	    						height:'100%',
	    						width:'70%',
	    						textAlign:'center',
	    						textAlignVertical:'center',
	    						fontSize:15,
	    						fontWeight:'bold',
	    						color:'#000',
	    						left: '28%'
	    				}}>
	    					Add a new dish in your menu
	    				</Text>
	    			</View>

	    			<View style={{
						    borderColor: '#ddd',
						    borderBottomWidth: 0,
						    shadowColor: '#000',
						    shadowOffset: {
								width: 0,
								height: 5,
							},
							shadowOpacity: 0.34,
							shadowRadius: 6.27,
							elevation: 10,
						    backgroundColor: '#fff',
		    				height: '75%',
		    				width:'90%',
		    				position: 'relative',
		    				elevation: 11,
							top: '5%',
						    alignItems: 'center',
						    paddingTop: '5%',
						    borderRadius: 25
	    			}}>
	    				<ScrollView
	    					style = {{width:'100%'}}
	    					contentContainerStyle = {{alignItems:'center',paddingBottom:50}}>
		    				<Text style = {{
		    						height: 23,
		    						width: '50%',
		    						position: 'relative',
		    						fontSize: 15,
		    						fontWeight: 'bold',
		    						textAlign: 'center',
		    						textAlignVertical : 'center',
		    						color: '#000'
		    				}}>
		    					Name of Dish
		    				</Text>
		    				<Text style = {{
		    						height: 20,
		    						width: '100%',
		    						position: 'relative',
		    						fontSize: 13,
		    						textAlign: 'center',
		    						textAlignVertical : 'center',
		    						color: '#000'
		    				}}>
		    					{'Maximum of '+Constants.CREDENTIALS_POLICY.MAX_FOOD_MENU+' input characters only'}
		    				</Text>
		    				<View style={{
			    					height:42,
			    					width: '60%',
			    					borderRadius: 100,
			    					position: 'relative',
			    					alignItems:'center',
			    					borderWidth: 2
			    			}}>
			    				<TextInput
			    					placeholder = 'create name of dish'
			    					style={{
			    						width:'90%',
			    						height:'100%',
			    						textAlign:'center',
			    						textAlignVertical:'center'
			    					}}
			    					maxLength = {Constants.CREDENTIALS_POLICY.MAX_FOOD_MENU}
			    					onChangeText={(inputNameOfDish)=>this.setState({inputNameOfDish})}/>
		    				</View>

		    				<Text style = {{
		    						height: 23,
		    						width: '50%',
		    						position: 'relative',
		    						fontSize: 15,
		    						fontWeight: 'bold',
		    						textAlign: 'center',
		    						textAlignVertical : 'center',
		    						color: '#000',
		    						top: '2%'
		    				}}>
		    					Description 
		    				</Text>
		    				<Text style = {{
		    						height: 19,
		    						width: '100%',
		    						position: 'relative',
		    						fontSize: 13,
		    						textAlign: 'center',
		    						textAlignVertical : 'center',
		    						color: '#000',
		    						top: '2%'
		    				}}>
		    					{'(Optional)Maximum of '+Constants.CREDENTIALS_POLICY.MAX_DISH_DESCRIPTION+' input characters only'}
		    				</Text>
		    				<View style={{
			    					height:43,
			    					width: '60%',
			    					borderRadius: 100,
			    					position: 'relative',
			    					borderWidth:2,
			    					alignItems:'center',
			    					top: '2%'
			    			}}>
			    				<TextInput
			    					placeholder = 'description of your dish'
			    					style={{
			    						width:'90%',
			    						height:'100%',
			    						textAlign:'center',
			    						textAlignVertical:'center'
			    					}}
			    					maxLength = {Constants.CREDENTIALS_POLICY.MAX_DISH_DESCRIPTION}
			    					onChangeText={(inputDescription)=>this.setState({inputDescription})}/>
		    				</View>

		    				<Text style = {{
		    						height: 23,
		    						width: '50%',
		    						position: 'relative',
		    						fontSize: 15,
		    						fontWeight: 'bold',
		    						textAlign: 'center',
		    						textAlignVertical : 'center',
		    						color: '#000',
		    						top: '4%'
		    				}}>
		    					Price
		    				</Text>

		    				<View style={{
			    					height:43,
			    					width: '60%',
			    					borderRadius: 100,
			    					position: 'relative',
			    					borderWidth:2,
			    					alignItems:'center',
			    					top: '4%'
			    			}}>
			    				<TextInput
			    					placeholder = 'Input price in pesos'
			    					style={{
			    						width:'90%',
			    						height:'100%',
			    						textAlign:'center',
			    						textAlignVertical:'center'
			    					}}
			    					onChangeText={(inputFoodPrice)=>this.setState({inputFoodPrice})}/>
		    				</View>

		    				<View style={{
			    					height:46,
			    					width: '100%',
			    					position: 'relative',
			    					flexDirection: 'row',
			    					top: '6%'
			    			}}>
			    				<Text style = {{
			    						height: '100%',
			    						textAlignVertical: 'center',
			    						textAlign: 'center',
			    						position : 'relative',
			    						left: 10,
			    						width: '25%',
			    						fontSize: 15,
			    						fontWeight: 'bold',
			    						color: '#000'
			    				}}>
			    					Good for: 
			    				</Text>
			    				<View style = {{
			    						width: '55%',
			    						height: '100%',
			    						position: 'relative',
			    						borderRadius: 100,
			    						borderWidth: 2,
			    						left: 10
			    				}}>
			    					<Picker
			    						selectedValue = {this.state.inputNumberOfPersons}
			    						onValueChange = {this.onChangeNumberOfPersons}
							        	style={{height:'100%',width:'100%',position:'relative'}}
							        	itemStyle = {{textAlign: 'center',fontSize:15}}
							        	>
						        		<Picker.Item 
						        			label={'1 Person'} 
			        						value={'1 Person'}/>
			        					<Picker.Item 
						        			label={'1-2 Persons'} 
			        						value={'1-2 Persons'}/>
			        					<Picker.Item 
						        			label={'3 Persons'} 
			        						value={'3 Persons'}/>
			        					<Picker.Item 
						        			label={'3-4 Persons'} 
			        						value={'3-4 Persons'}/>
			        					<Picker.Item 
						        			label={'5 Persons or more'} 
			        						value={'5 Persons or more'}/>
			        				</Picker>
			    				</View>
			    			</View>

			    			<View style={{
			    					height:46,
			    					width: '100%',
			    					position: 'relative',
			    					flexDirection: 'row',
			    					top: '8%'
			    			}}>
			    				<Text style = {{
			    						height: '100%',
			    						textAlignVertical: 'center',
			    						textAlign: 'center',
			    						position : 'relative',
			    						left: 10,
			    						width: '25%',
			    						fontSize: 15,
			    						fontWeight: 'bold',
			    						color: '#000'
			    				}}>
			    					Food type: 
			    				</Text>
			    				<View style = {{
			    						width: '55%',
			    						height: '100%',
			    						position: 'relative',
			    						borderRadius: 100,
			    						borderWidth: 2,
			    						left: 10
			    				}}>
			    					<Picker
			    						selectedValue = {this.state.inputFoodType}
			    						onValueChange = {this.onChangeFoodType}
							        	style={{height:'100%',width:'100%',position:'relative'}}
							        	itemStyle = {{textAlign: 'center',fontSize:15}}
							        	>
							        	<Picker.Item 
						        			label={'Vegetable'} 
			        						value={'Vegetable'}/>
						        		<Picker.Item 
						        			label={'Sea-food'} 
			        						value={'Sea-food'}/>
			        					<Picker.Item 
						        			label={'Chicken'} 
			        						value={'Chicken'}/>
			        					<Picker.Item 
						        			label={'Pork'} 
			        						value={'Pork'}/>
			        					<Picker.Item 
						        			label={'Dessert'} 
			        						value={'Dessert'}/>
			        					<Picker.Item 
						        			label={'Beverages'} 
			        						value={'Beverages'}/>
		        						<Picker.Item 
						        			label={'Snacks'} 
			        						value={'Snacks'}/>
			        					<Picker.Item 
						        			label={'Beef'} 
			        						value={'Beef'}/>
			        					<Picker.Item 
						        			label={'Breakfast'} 
			        						value={'Breakfast'}/>
			        					<Picker.Item 
						        			label={'Silog'} 
			        						value={'Silog'}/>
			        				</Picker>
			    				</View>
			    			</View>

			    			<TouchableWithoutFeedback
			    				onPress = {()=>this.submitMenuInfo()}>
				    			<Text style={{
		    							height: 46,
				    					width:'40%',
				    					position:'relative',
									    borderColor: '#ddd',
									    borderBottomWidth: 0,
									    shadowColor: '#000',
									    shadowOffset: {
											width: 0,
											height: 2,
										},
										shadowOpacity: 0.34,
										elevation: 6,
									    backgroundColor: '#fff',
									    textAlignVertical: 'center',
									    textAlign: 'center',
									    fontWeight :'bold',
									    borderRadius : 15,
									    top: '15%',
									    fontSize: 15,
									    color: '#000',
									    marginBottom:20
		    					}}>
		    						Submit
				    			</Text>	
				    		</TouchableWithoutFeedback>
				    	</ScrollView>
				    	<Text 
			    			style={{
			    				height:'9%',
			    				width:'11%',
			    				position:'absolute',
			    				textAlign: 'center',
			    				textAlignVertical:'center',
			    				top: '96%',
			    				left: '3%'
			    		}}>
			    			<Icon
			    				style={{fontSize:30,color:'#000'}}
			    				name = 'ios-arrow-down'
			    				type = 'Ionicons'/>

			    		</Text>
	    			</View>
	    		</View>
	    	</React.Fragment>
		);
  	}
}