import React, { Component } from "react";
import Constants from "./Constants";
import { Text, View, TouchableWithoutFeedback, ScrollView } from "react-native";
import { Icon } from "native-base";

export default class Manual extends Component {
  state = {};

  render() {
    return (
      <React.Fragment>
        <View
          style={{
            height: "9%",
            width: "100%",
            position: "absolute",
            backgroundColor: "#555dff"
          }}
        >
          <TouchableWithoutFeedback
            onPress={() =>
              this.props.doChangeMainAppDisplay(
                Constants.APP_PAGES.WELCOME_APP_PAGE
              )
            }
          >
            <Text
              style={{
                height: "50%",
                width: "18%",
                position: "relative",
                borderWidth: 2,
                color: "#000",
                fontSize: 13,
                fontWeight: "bold",
                textAlign: "center",
                textAlignVertical: "center",
                borderRadius: 100,
                top: "20%",
                left: "2%"
              }}
            >
              RETURN
            </Text>
          </TouchableWithoutFeedback>
        </View>
        <View
          style={{
            height: "100%",
            width: "100%",
            position: "relative",
            alignItems: "center"
          }}
        >
          <View
            style={{
              borderWidth: 1.2,
              borderColor: "#ddd",
              borderBottomWidth: 0,
              shadowColor: "#000",
              shadowOffset: {
                width: 0,
                height: 5
              },
              shadowOpacity: 0.34,
              shadowRadius: 6.27,
              elevation: 10,
              backgroundColor: "#fff",
              height: "70%",
              width: "90%",
              borderWidth: 2,
              borderRadius: 20,
              top: "16%",
              paddingTop: "5%"
            }}
          >
            <ScrollView
              style={{ width: "100%" }}
              contentContainerStyle={{ alignItems: "center" }}
            >
              <Text
                style={{
                  width: "90%",
                  position: "relative",
                  textAlign: "center",
                  fontSize: 14,
                  justifyContent: "center",
                  fontWeight: "bold",
                  color: "#e6094f"
                }}
              >
                {`Terms & Conditions:
                `}
              </Text>
              <Text
                style={{
                  width: "90%",
                  position: "relative",
                  textAlign: "center",
                  fontSize: 14,
                  fontColor: "#000",
                  justifyContent: "center",
                  fontWeight: "bold"
                }}
              >
                {`1. Users of this system agree to be bound by these terms and conditions. Your use of and access to this system indicate your acceptance of these terms and conditions.

2. All material on this system is protected by the admins of "Asa ta kaon?".

3. "Asa ta kaon?" will have no responsibility or liability in relation to any loss or damage which you incur, including damage to your software or hardware, arising from your use of or access to this site.

4.If you have a concern regarding our system please do send a report for the admins to check the system and maintain a suitable system for users's convenience.

5. Booking is booked for the day and is noted that your booking will be reserved for 1 hour and can only book once a day. No double booking.
                `}
              </Text>
              <Text
                style={{
                  width: "90%",
                  position: "relative",
                  textAlign: "center",
                  fontSize: 14,
                  color: "#e6094f",
                  justifyContent: "center",
                  fontWeight: "bold"
                }}
              >
                {`
                `}
              </Text>
              <Text
                style={{
                  width: "90%",
                  position: "relative",
                  textAlign: "center",
                  fontSize: 14,
                  fontColor: "#000",
                  justifyContent: "center",
                  fontWeight: "bold"
                }}
              >
                {``}
              </Text>
            </ScrollView>

            <Text
              style={{
                height: "9%",
                width: "11%",
                position: "absolute",
                textAlign: "center",
                textAlignVertical: "center",
                top: "98%",
                left: "3%"
              }}
            >
              <Icon
                style={{ fontSize: 30, color: "#000" }}
                name="ios-arrow-down"
                type="Ionicons"
              />
            </Text>
          </View>
        </View>
      </React.Fragment>
    );
  }
}
