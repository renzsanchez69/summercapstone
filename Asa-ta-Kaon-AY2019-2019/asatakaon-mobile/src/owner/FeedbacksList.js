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
	FlatList,
	ScrollView} 
	from 'react-native';
import {
	Container, 
	Icon,
	Spinner} 
	from 'native-base';
/* -- Custom Components  -- */
import Constants from '../commons/Constants.js';

export default class FeedbacksList extends Component{

	state = {
		allReceivedComments : []
	}

	componentDidMount(){
		if(this.props.doGetLoggedInformation.comments){
			const commentsWithKey = JSON.parse(JSON.stringify(this.props.doGetLoggedInformation.comments));
			const initAllReceivedComments = [];
			Object
				.keys(commentsWithKey)
				.forEach((commentKey)=>{
					initAllReceivedComments.push(commentsWithKey[commentKey]);
				});
			this.setState({allReceivedComments:initAllReceivedComments});
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
    						width:'45%',
    						textAlign:'center',
    						textAlignVertical:'center',
    						fontSize:15,
    						fontWeight:'bold',
    						color:'#000',
    						left: '60%'
    				}}>
    					Users feedback
    				</Text>
    			</View>
    			<View style ={{
		    			height: '80%',
		    			width: '95%',
		    			position: 'relative',
		    			top: '10%'
		    	}}>
		    		{
		    			this.props.doGetLoggedInformation.comments ?
		    			<React.Fragment>
		    				<FlatList
		    					data = {this.state.allReceivedComments}
		    					renderItem = {({item}) =>
		    						<View style ={{
		    								height: 73,
		    								paddingTop: 5,
		    								width: '95%',
		    								position: 'relative',
		    								left: '2.5%',
		    								borderBottomWidth: 2
		    						}}>
		    							<Text style ={{
		    									height: '100%',
		    									width: '100%',
		    									paddingTop: '2%',
		    									paddingLeft: '2%',
		    									fontSize: 11.5,
		    									color: '#000'
		    							}}>
		    								{item.comment}
		    							</Text>
		    						</View>
		    					}
		    					keyExtractor={item => item.key}/>
		    			</React.Fragment>
		    			:
		    			<Text
		    				style ={{
		    					height: 50,
		    					width: '100%',
		    					position: 'relative',
		    					textAlignVertical: 'center',
		    					textAlign: 'center',
		    					fontSize: 13,
		    					top: '35%',
		    					color: '#000',
		    					fontWeight: 'bold'
		    				}}>
		    				No current feedbacks received
		    			</Text>
		    		}
		    	</View>
    		</View>
		);
	}
}