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
	CheckBox} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
/* -- Custom Components  -- */
import Constants   from './Constants.js';


export default class UserInfo extends Component{



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
								height: 0,
							},
							shadowOpacity: 0.34,
							shadowRadius: 6.27,
							elevation: 9,
						    backgroundColor: '#555dff'
	    			}}>
	    				<Text style={{
	    						height:'100%',
	    						width:'100%',
	    						textAlign:'center',
	    						textAlignVertical:'center',
	    						fontSize:15,
	    						fontWeight:'bold',
	    						color:'#000'
	    				}}>
	    					Account Information
	    				</Text>
	    			</View>

	    			<View style={{
	    					height:'10%',
	    					width:'100%',
	    					flexDirection: 'row',
	    					justifyContent:'center',
	    					top: '10%'
	    			}}>
	    				<Text style={{
	    						height:'100%',
	    						width:'10%',
	    						position:'relative',
	    						fontSize: 15,
	    						fontWeight:'bold',
	    						textAlign:'center',
	    						textAlignVertical:'center'
	    				}}>
	    					<Icon
	    						style={{
	    							fontSize:34
	    						}}
	    						name = 'sign-out'
	    						type = 'FontAwesome'/>
	    				</Text>
	    				<TouchableWithoutFeedback
	    					onPress={()=>this.props.doSignOutAccount()}>
		    				<Text style={{
		    						height:'100%',
		    						width:'50%',
		    						fontSize:16,
		    						color:'#000',
		    						borderRadius:100,
		    						backgroundColor:'#fff',
		    						fontWeight:'bold',
		    						textAlignVertical:'center',
		    						textAlign:'center',
								    borderColor: '#ddd',
								    borderBottomWidth: 0,
								    shadowColor: '#000',
								    shadowOffset: {
										width: 0,
										height: 2,
									},
									shadowOpacity: 0.34,
									shadowRadius: 2.27,
									elevation: 9,
		    				}}>
		    					Sign-out
		    				</Text>
		    			</TouchableWithoutFeedback>
	    			</View>

	    			<View style={{
	    					height:'10%',
	    					width:'100%',
	    					flexDirection: 'row',
	    					justifyContent:'center',
	    					top: '15%'
	    			}}>
	    				<Text style={{
	    						height:'100%',
	    						width:'10%',
	    						position:'relative',
	    						fontSize: 15,
	    						fontWeight:'bold',
	    						textAlign:'center',
	    						textAlignVertical:'center'
	    				}}>
	    					<Icon
	    						style={{
	    							fontSize:34
	    						}}
	    						name = 'lastpass'
	    						type = 'MaterialCommunityIcons'/>
	    				</Text>
	    				<TouchableWithoutFeedback
	    					onPress={()=>this.props.doSetHomePage(Constants.COMMON_ROLE_PAGES.CHANGE_PASSWORD)}>
		    				<Text style={{
		    						height:'100%',
		    						width:'50%',
		    						fontSize:16,
		    						color:'#000',
		    						borderRadius:100,
		    						backgroundColor:'#fff',
		    						fontWeight:'bold',
		    						textAlignVertical:'center',
		    						textAlign:'center',
								    borderColor: '#ddd',
								    borderBottomWidth: 0,
								    shadowColor: '#000',
								    shadowOffset: {
										width: 0,
										height: 2,
									},
									shadowOpacity: 0.34,
									shadowRadius: 2.27,
									elevation: 9
		    				}}>
		    					Change Password
		    				</Text>
		    			</TouchableWithoutFeedback>
	    			</View>

	    			<View style={{
	    					height:'10%',
	    					width:'100%',
	    					flexDirection: 'row',
	    					justifyContent:'center',
	    					top: '20%'
	    			}}>
	    				<Text style={{
	    						height:'100%',
	    						width:'10%',
	    						position:'relative',
	    						fontSize: 15,
	    						fontWeight:'bold',
	    						textAlign:'center',
	    						textAlignVertical:'center'
	    				}}>
	    					<Icon
	    						style={{
	    							fontSize:34
	    						}}
	    						name = 'user'
	    						type = 'Entypo'/>
	    				</Text>
	    				<TouchableWithoutFeedback
	    					onPress={()=>this.props.doSetHomePage(Constants.COMMON_ROLE_PAGES.ACCOUNT_DETAILS)}>
		    				<Text style={{
		    						height:'100%',
		    						width:'50%',
		    						fontSize:16,
		    						color:'#000',
		    						borderRadius:100,
		    						backgroundColor:'#fff',
		    						fontWeight:'bold',
		    						textAlignVertical:'center',
		    						textAlign:'center',
								    borderColor: '#ddd',
								    borderBottomWidth: 0,
								    shadowColor: '#000',
								    shadowOffset: {
										width: 0,
										height: 2,
									},
									shadowOpacity: 0.34,
									shadowRadius: 2.27,
									elevation: 9
		    				}}>
		    					Account Details
		    				</Text>
		    			</TouchableWithoutFeedback>

	    			</View>
	    			<View style={{
	    					height:'10%',
	    					width:'100%',
	    					flexDirection: 'row',
	    					justifyContent:'center',
	    					top: '25%'
	    			}}>
	    				<Text style={{
	    						height:'100%',
	    						width:'10%',
	    						position:'relative',
	    						fontSize: 15,
	    						fontWeight:'bold',
	    						textAlign:'center',
	    						textAlignVertical:'center'
	    				}}>
	    					<Icon
	    						style={{
	    							fontSize:34
	    						}}
	    						name = 'message'
	    						type = 'Entypo'/>
	    				</Text>
	    				<TouchableWithoutFeedback
	    					onPress={()=>this.props.doSetHomePage(Constants.COMMON_ROLE_PAGES.SEND_A_REPORT)}>
		    				<Text style={{
		    						height:'100%',
		    						width:'50%',
		    						fontSize:16,
		    						color:'#000',
		    						borderRadius:100,
		    						backgroundColor:'#fff',
		    						fontWeight:'bold',
		    						textAlignVertical:'center',
		    						textAlign:'center',
								    borderColor: '#ddd',
								    borderBottomWidth: 0,
								    shadowColor: '#000',
								    shadowOffset: {
										width: 0,
										height: 2,
									},
									shadowOpacity: 0.34,
									shadowRadius: 2.27,
									elevation: 9
		    				}}>
		    					Send A Report
		    				</Text>
		    			</TouchableWithoutFeedback>
		    				
	    			</View>
	    		</View>
	    	</React.Fragment>
		);
  	}
}
