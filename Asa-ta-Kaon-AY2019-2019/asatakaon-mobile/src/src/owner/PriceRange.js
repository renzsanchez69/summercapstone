import React, 
	{Component} 
	from 'react';
import {Platform, 
	StyleSheet, 
	Text, 
	View, 
	Image,
	NetInfo,
	TouchableWithoutFeedback,
	TextInput,
	Picker,
	CheckBox,
	ScrollView} 
	from 'react-native';

	
/* -- Custom Components  -- */
import Constants from '../commons/Constants.js';

export default class PriceRange extends Component{

	state = {
		inputMinimum : '00000000',
		inputMaximum : '00000000'
	}

	componentDidMount(){
		this.props.doUseFirebaseObject
				.database()
				.ref("RESTAURANT/"
					+String(this.props.doGetLoggedInformation.key)
					+"/priceRange")
				.once("value",snapshot=>{
						if(snapshot.exists()){
							const priceRange = JSON.parse(JSON.stringify(snapshot.val()));
							this.setState({
								inputMinimum : String(priceRange.minimum),
								inputMaximum : String(priceRange.maximum)
							});
						}
				});
	}

	validatePriceInputs = ()=>{
		const currentMin = this.state.inputMinimum;
		const currentMax = this.state.inputMaximum;
		if(Number.isInteger(Number(currentMin)) == false
			|| Number.isInteger(Number(currentMax)) == false){
			return false;
		}
		else return true;
	}

	submitPriceRange = ()=>{
		this.props.doSendAReportMessage('Submitting, please wait..');
		if(this.validatePriceInputs() == false){
			this.props.doSendAReportMessage('Invalid price inputs, please try again');
			setTimeout(()=>{
				this.props.doSendAReportMessage('');
			},Constants.REPORT_DISPLAY_TIME);	
		}
		else{
			this.props.doUseFirebaseObject
				.database()
				.ref("RESTAURANT/"
					+String(this.props.doGetLoggedInformation.key)
					+"/priceRange/")
				.update({
					'minimum' : String(Math.floor(Number(this.state.inputMinimum))),
					'maximum' : String(Math.floor(Number(this.state.inputMaximum)))
				})
				.then(()=>{
					this.props.doSendAReportMessage('Success, please wait..');
					setTimeout(()=>{
						this.props.doSetHomePage(Constants.OWNER_ROLE_PAGES.RESTAURANT_INFO);
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				})
				.catch((error)=>{
					this.props.doSendAReportMessage('Error in connecting to the server');
					setTimeout(()=>{
						this.props.doSendAReportMessage('');
					},Constants.REPORT_DISPLAY_TIME);
				});
		}
	}

	render(){
		return(
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
    						width:'50%',
    						textAlign:'center',
    						textAlignVertical:'center',
    						fontSize:15,
    						fontWeight:'bold',
    						color:'#000',
    						left: '65%'
    				}}>
    					{"Restaurant's Price Range"}
    				</Text>
    			</View>

    			<View style={{
	    				borderWidth: 1.2,
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
	    				height: '60%',
	    				width:'90%',
	    				position: 'relative',
	    				elevation: 11,
						top: '15%',
					    alignItems: 'center',
					    paddingTop: '5%',
					    borderRadius: 25
	    		}}>
	    			<Text style={{
	    					height:'9.5%',
	    					width:'50%',
	    					textAlign:'center',
	    					textAlignVertical:'center',
	    					fontSize: 16,
	    					fontWeight: 'bold',
	    					color: '#000'
	    			}}>
	    				Minimum Price
	    			</Text>
	    			<View style={{
	    					height:'15%',
	    					width: '60%',
	    					borderRadius: 100,
	    					position: 'relative',
	    					borderWidth:2,
	    					alignItems:'center'
	    			}}>
	    				<TextInput
	    					maxLength = {8}
	    					value = {this.state.inputMinimum}
	    					style={{
	    						width:'90%',
	    						height:'100%',
	    						textAlign:'center',
	    						textAlignVertical:'center'
	    					}}
	    					onChangeText={(inputMinimum)=>this.setState({inputMinimum})}/>
	    			</View>

	    			<Text style={{
	    					height:'9.5%',
	    					width:'50%',
	    					textAlign:'center',
	    					textAlignVertical:'center',
	    					fontSize: 16,
	    					fontWeight: 'bold',
	    					color: '#000'
	    			}}>
	    				Maximum Price
	    			</Text>
	    			<View style={{
	    					height:'15%',
	    					width: '60%',
	    					borderRadius: 100,
	    					position: 'relative',
	    					borderWidth:2,
	    					alignItems:'center'
	    			}}>
	    				<TextInput
	    					maxLength = {8}
	    					value = {this.state.inputMaximum}
	    					style={{
	    						width:'90%',
	    						height:'100%',
	    						textAlign:'center',
	    						textAlignVertical:'center'
	    					}}
	    					onChangeText={(inputMaximum)=>this.setState({inputMaximum})}/>
	    			</View>
	    			<Text style={{
	    					height:'9.5%',
	    					width:'90%',
	    					textAlignVertical:'center',
	    					fontSize: 13,
	    					fontWeight: 'bold',
	    					color: '#000',
	    					paddingLeft: '5%',
	    					top: '3%'
	    			}}>
	    				{'Current Price Range: '+Math.floor(Number(this.state.inputMinimum))
	    				+'-'+Math.floor(Number(this.state.inputMaximum))+' in pesos'}
	    			</Text>

	    			<TouchableWithoutFeedback
	    				onPress = {()=>this.submitPriceRange()}>
		    			<Text style={{
		    					height: '15.5%',
		    					width:'50%',
		    					position:'relative',
		    					textAlignVertical:'center',
		    					textAlign:'center',
		    					borderRadius: 100,
		    					fontWeight:'bold',
		    					borderWidth: 2,
		    					borderColor: '#000',
		    					color: '#000',
		    					borderWidth: 1.2,
							    borderColor: '#ddd',
							    borderBottomWidth: 0,
							    shadowColor: '#000',
							    shadowOffset: {
									width: 0,
									height: 5,
								},
								top:'16%',
								shadowOpacity: 0.34,
								shadowRadius: 6.27,
								elevation: 10,
							    backgroundColor: '#fff',
							    fontSize: 15
		    			}}>
		    				Submit
		    			</Text>
		    		</TouchableWithoutFeedback>
	    		</View>
    		</View>
		);
	}
}
